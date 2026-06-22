<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RuntimeException;

class MomoService
{
    protected string $baseUrl;
    protected string $environment;
    protected string $subscriptionKey;
    protected string $apiUser;
    protected string $apiKey;
    protected string $currency;

    public function __construct()
    {
        $this->baseUrl         = rtrim(config('momo.base_url'), '/');
        $this->environment     = config('momo.environment');
        $this->subscriptionKey = config('momo.subscription_key');
        $this->apiUser         = config('momo.api_user');
        $this->apiKey          = config('momo.api_key');
        $this->currency        = config('momo.currency');

        foreach (['subscriptionKey', 'apiUser', 'apiKey'] as $required) {
            if (empty($this->$required)) {
                throw new RuntimeException("MoMo config missing: {$required}. Check your .env file.");
            }
        }
    }

    /**
     * Get a cached access token, fetching a new one if expired.
     * MTN tokens last ~3600s; we cache for 55 minutes to be safe.
     */
    public function getAccessToken(): string
    {
        return Cache::remember('momo_access_token', now()->addMinutes(55), function () {
            $response = Http::withBasicAuth($this->apiUser, $this->apiKey)
                ->withHeaders([
                    'Ocp-Apim-Subscription-Key' => $this->subscriptionKey,
                    'User-Agent'                 => 'curl/7.81.0',
                ])
                ->withBody('', 'application/json') // forces Content-Length: 0, avoids 411
                ->post("{$this->baseUrl}/collection/token/");

            if (! $response->successful()) {
                Log::error('MoMo token request failed', ['body' => $response->body()]);
                throw new RuntimeException('Could not obtain MoMo access token.');
            }

            return $response->json('access_token');
        });
    }

    /**
     * Initiate a Request to Pay. Returns the reference UUID we generated,
     * which MTN will use to identify this transaction in callbacks and
     * status checks. This call only confirms MTN *accepted* the request —
     * not that the customer approved it yet.
     *
     * @param string $phone MSISDN, e.g. 2567xxxxxxxx (no '+', no spaces)
     * @param float  $amount
     * @param string $externalId  Your own internal reference (e.g. payment ID)
     * @param string $payerMessage Shown to the payer on their phone prompt
     */
    public function requestToPay(string $phone, float $amount, string $externalId, string $payerMessage = 'Gym membership payment'): string
    {
        $referenceId = (string) Str::uuid();
        $token = $this->getAccessToken();

        // MTN's sandbox WAF rejects request bodies containing certain special
        // characters (confirmed: '#' triggers a block) inside payerMessage/payeeNote.
        // Strip to plain alphanumeric + spaces to stay safely within accepted input.
        $safeMessage = $this->sanitizeMessage($payerMessage);

        $headers = [
            'X-Reference-Id'            => $referenceId,
            'X-Target-Environment'      => $this->environment,
            'Ocp-Apim-Subscription-Key' => $this->subscriptionKey,
            'Content-Type'               => 'application/json',
            'User-Agent'                 => 'curl/7.81.0',
        ];

        // Explicit callback URL, in addition to providerCallbackHost set at
        // API user creation. Some MTN sandbox accounts only fire the webhook
        // when X-Callback-Url is present on the request itself.
        $callbackSecret = config('momo.callback_secret');
        if (! empty($callbackSecret)) {
            $headers['X-Callback-Url'] = route('momo.callback', ['secret' => $callbackSecret]);
        }

        $response = Http::withToken($token)
            ->withHeaders($headers)
            ->post("{$this->baseUrl}/collection/v1_0/requesttopay", [
                'amount'       => (string) round($amount),
                'currency'     => $this->currency,
                'externalId'   => $externalId,
                'payer'        => [
                    'partyIdType' => 'MSISDN',
                    'partyId'     => $this->normalizePhone($phone),
                ],
                'payerMessage' => $safeMessage,
                'payeeNote'    => $safeMessage,
            ]);

        // 202 Accepted = MTN queued the request and will push it to the phone.
        if ($response->status() !== 202) {
            Log::error('MoMo requestToPay failed', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            throw new RuntimeException('MoMo rejected the payment request: ' . $response->body());
        }

        return $referenceId;
    }

    /**
     * Check the status of a previously-initiated Request to Pay.
     * Returns the raw decoded response, e.g.:
     * ['status' => 'PENDING'|'SUCCESSFUL'|'FAILED', 'financialTransactionId' => ..., 'reason' => ...]
     */
    public function checkTransactionStatus(string $referenceId): array
    {
        $token = $this->getAccessToken();

        $response = Http::withToken($token)
            ->withHeaders([
                'X-Target-Environment'      => $this->environment,
                'Ocp-Apim-Subscription-Key' => $this->subscriptionKey,
                'User-Agent'                 => 'curl/7.81.0',
            ])
            ->get("{$this->baseUrl}/collection/v1_0/requesttopay/{$referenceId}");

        if (! $response->successful()) {
            Log::error('MoMo status check failed', [
                'reference' => $referenceId,
                'status'    => $response->status(),
                'body'      => $response->body(),
            ]);
            throw new RuntimeException('Could not check MoMo transaction status.');
        }

        return $response->json();
    }

    /**
     * Applies MTN's reported status to our Payment/Subscription records.
     * Idempotent — safe to call multiple times with the same SUCCESSFUL status.
     * Shared by the controller (webhook + polling) and the scheduled
     * reconciliation command, so all three paths behave identically.
     */
    public function applyMomoResult(Payment $payment, Subscription $subscription, string $momoStatus): void
    {
        $momoStatus = strtoupper($momoStatus);

        // Safeguard: once a payment has been recorded as failed, never let a
        // later contradictory SUCCESSFUL response silently flip it back.
        // MTN's sandbox has been observed giving inconsistent results across
        // repeated status checks on the same reference. If this happens, log
        // it loudly and require manual review rather than auto-granting access.
        if ($payment->momo_status === 'failed') {
            if ($momoStatus === 'SUCCESSFUL') {
                Log::error('MoMo: contradictory SUCCESSFUL received for a payment already marked failed. Ignoring — manual review required.', [
                    'payment_id'        => $payment->id,
                    'subscription_id'   => $subscription->id,
                    'momo_reference_id' => $payment->momo_reference_id,
                ]);
            }
            return;
        }

        if ($momoStatus === 'SUCCESSFUL' && $payment->momo_status !== 'successful') {
            $amountPaid = $payment->amount_paid + $payment->momo_amount_requested;
            $amountDue  = $payment->amount_due;
            $balance    = max($amountDue - $amountPaid, 0);

            $status = $amountPaid >= $amountDue ? 'paid' : ($amountPaid > 0 ? 'half-paid' : 'unpaid');

            $payment->update([
                'momo_status'  => 'successful',
                'amount_paid'  => $amountPaid,
                'balance'      => $balance,
                'status'       => $status,
                'paid_at'      => now(),
            ]);

            if ($status === 'paid') {
                $subscription->update(['status' => 'active']);
            }
        } elseif ($momoStatus === 'FAILED' && $payment->momo_status !== 'failed') {
            $payment->update(['momo_status' => 'failed']);
        }
        // PENDING -> no change, still waiting.
    }

    /**
     * Strips characters MTN's sandbox WAF has been observed to reject inside
     * payerMessage/payeeNote (confirmed trigger: '#'). Keeps letters, numbers,
     * spaces, and basic punctuation that's been tested safe.
     */
    protected function sanitizeMessage(string $message): string
    {
        $clean = preg_replace('/[^A-Za-z0-9 .,\-]/', '', $message);
        $clean = trim(preg_replace('/\s+/', ' ', $clean));

        return $clean !== '' ? substr($clean, 0, 160) : 'Payment';
    }

    /**
     * MTN expects MSISDN without '+' and without leading '0' replaced by country code issues.
     * Accepts formats like 0772123456, 772123456, 256772123456, +256772123456
     * and normalizes to 256772123456 (adjust default country code if not Uganda).
     */
    protected function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone); // strip non-digits

        if (Str::startsWith($phone, '0')) {
            $phone = '256' . substr($phone, 1);
        } elseif (! Str::startsWith($phone, '256') && strlen($phone) === 9) {
            $phone = '256' . $phone;
        }

        return $phone;
    }
}