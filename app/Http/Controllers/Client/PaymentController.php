<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Subscription;
use App\Services\MomoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RuntimeException;

class PaymentController extends Controller
{
    public function show($subscriptionId)
    {
        $subscription = Subscription::where('id', $subscriptionId)
            ->where('user_id', Auth::id())
            ->with('membership')
            ->firstOrFail();

        $payment = Payment::where('subscription_id', $subscription->id)
            ->first();

        return view('client.payment', compact('subscription', 'payment'));
    }

    /**
     * Initiates payment. For MoMo, this no longer marks the payment as paid —
     * it only KICKS OFF a Request-to-Pay prompt on the client's phone.
     * The payment stays 'pending' until MTN's webhook (momoCallback) confirms
     * SUCCESSFUL, or the client polls and sees the final state.
     */
    public function process(Request $request, $subscriptionId)
    {
        $request->validate([
            'payment_method' => ['required', 'in:momo,airtel'],
            'phone'          => ['required', 'string', 'min:9', 'max:15'],
            'amount'         => ['required', 'numeric', 'min:1000'],
        ]);

        $subscription = Subscription::where('id', $subscriptionId)
            ->where('user_id', Auth::id())
            ->with('membership')
            ->firstOrFail();

        $amountDue   = $subscription->effective_price;
        $amountToPay = (float) $request->amount;

        if ($request->payment_method !== 'momo') {
            // Airtel Money not yet integrated — keep old manual behavior for now.
            return redirect()->route('client.payment', $subscription->id)
                ->with('error', 'Airtel Money is not yet available. Please choose MTN MoMo.');
        }

        $payment = Payment::where('subscription_id', $subscription->id)->first();

        // Block a new attempt while one is already pending, to avoid double prompts.
        if ($payment && $payment->momo_status === 'pending') {
            return redirect()->route('client.payment', $subscription->id)
                ->with('error', 'A payment request is already pending on your phone. Please approve or reject it first.');
        }

        $payment = Payment::updateOrCreate(
            ['subscription_id' => $subscription->id],
            [
                'user_id'        => Auth::id(),
                'amount_due'     => $amountDue,
                // amount_paid / balance / status are NOT updated yet —
                // those only change once MTN confirms success.
                'payment_method' => $request->payment_method,
            ]
        );

        try {
            $momo = new MomoService();
            $externalId = (string) $payment->id;

            $referenceId = $momo->requestToPay(
                phone: $request->phone,
                amount: $amountToPay,
                externalId: $externalId,
                payerMessage: "Membership payment for subscription {$subscription->id}"
            );
        } catch (RuntimeException $e) {
            Log::error('MoMo requestToPay error', ['message' => $e->getMessage()]);

            return redirect()->route('client.payment', $subscription->id)
                ->with('error', 'Could not start the MoMo payment request. Please try again shortly.');
        }

        $payment->update([
            'momo_reference_id'      => $referenceId,
            'momo_status'            => 'pending',
            'momo_amount_requested'  => $amountToPay,
            'transaction_id'         => 'MOMO-' . strtoupper(Str::random(8)),
        ]);

        return redirect()->route('client.payment', $subscription->id)
            ->with('info', 'Check your phone and approve the payment prompt to complete your payment.');
    }

    /**
     * Lightweight polling endpoint the frontend can call every few seconds
     * while waiting for the client to approve/reject the prompt. Works as a
     * fallback alongside the webhook — useful in sandbox/local dev where
     * MTN cannot reach your callback URL.
     */
    public function checkStatus($subscriptionId)
    {
        $subscription = Subscription::where('id', $subscriptionId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $payment = Payment::where('subscription_id', $subscription->id)->firstOrFail();

        if (! $payment->momo_reference_id || $payment->momo_status !== 'pending') {
            return response()->json(['momo_status' => $payment->momo_status]);
        }

        try {
            $momo = new MomoService();
            $result = $momo->checkTransactionStatus($payment->momo_reference_id);
        } catch (RuntimeException $e) {
            return response()->json(['momo_status' => 'pending', 'error' => 'status_check_failed']);
        }

        $momo->applyMomoResult($payment, $subscription, $result['status'] ?? 'PENDING');

        return response()->json(['momo_status' => $payment->refresh()->momo_status]);
    }

    /**
     * MTN's webhook callback. We protect this route with a shared secret in
     * the query string (set via MOMO_CALLBACK_SECRET) since MoMo's webhook
     * payload itself carries no verifiable signature.
     *
     * Route should be registered OUTSIDE the 'auth' middleware group, e.g.:
     * Route::post('/momo/callback/{secret}', [PaymentController::class, 'momoCallback']);
     */
    public function momoCallback(Request $request, $secret)
    {
        if (! hash_equals((string) config('momo.callback_secret'), (string) $secret)) {
            Log::warning('MoMo callback rejected: bad secret');
            return response()->json(['error' => 'unauthorized'], 403);
        }

        $referenceId = $request->input('referenceId') ?? $request->input('financialTransactionId');
        $status      = strtoupper($request->input('status', 'PENDING'));

        Log::info('MoMo callback received', $request->all());

        $payment = Payment::where('momo_reference_id', $referenceId)->first();

        if (! $payment) {
            Log::warning('MoMo callback: no matching payment', ['referenceId' => $referenceId]);
            return response()->json(['received' => true]); // 200 so MTN doesn't retry forever
        }

        $subscription = $payment->subscription;
        $momo = new MomoService();
        $momo->applyMomoResult($payment, $subscription, $status);

        return response()->json(['received' => true]);
    }
}