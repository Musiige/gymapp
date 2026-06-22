<?php

namespace App\Console\Commands;

use App\Models\Payment;
use App\Services\MomoService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class ReconcileMomoPayments extends Command
{
    protected $signature = 'momo:reconcile';

    protected $description = 'Check MTN status for any payment still pending and apply the result. '
        . 'Safety net for cases where the webhook never fires and the client never polls again.';

    public function handle(): int
    {
        $pending = Payment::where('momo_status', 'pending')
            ->whereNotNull('momo_reference_id')
            ->with('subscription')
            ->get();

        if ($pending->isEmpty()) {
            $this->info('No pending MoMo payments to reconcile.');
            return self::SUCCESS;
        }

        $momo = new MomoService();
        $checked = 0;
        $resolved = 0;

        foreach ($pending as $payment) {
            if (! $payment->subscription) {
                Log::warning('Reconcile: payment has no subscription', ['payment_id' => $payment->id]);
                continue;
            }

            try {
                $result = $momo->checkTransactionStatus($payment->momo_reference_id);
                $checked++;

                $status = strtoupper($result['status'] ?? 'PENDING');
                if ($status !== 'PENDING') {
                    $momo->applyMomoResult($payment, $payment->subscription, $status);
                    $resolved++;
                    $this->info("Payment #{$payment->id}: resolved to {$status}");
                }
            } catch (RuntimeException $e) {
                Log::warning('Reconcile: status check failed', [
                    'payment_id' => $payment->id,
                    'error'      => $e->getMessage(),
                ]);
                // Leave as pending, try again next run.
            }
        }

        $this->info("Checked {$checked} pending payment(s), resolved {$resolved}.");
        return self::SUCCESS;
    }
}