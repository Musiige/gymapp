<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Services\NotificationService;
use Illuminate\Console\Command;

class SendPaymentReminders extends Command
{
    protected $signature   = 'gym:send-payment-reminders';
    protected $description = 'Send payment reminders to clients with unpaid or half-paid subscriptions';

    public function handle(NotificationService $notificationService): void
    {
        $subscriptions = Subscription::whereIn('status', ['active', 'pending'])
            ->whereHas('payment', function ($q) {
                $q->whereIn('status', ['unpaid', 'half-paid']);
            })
            ->orWhereDoesntHave('payment')
            ->with(['user', 'membership', 'payment'])
            ->get();

        $count = 0;

        foreach ($subscriptions as $subscription) {
            $user = $subscription->user;

            if (!$user->fcm_token) continue;

            $balance = $subscription->payment
                ? $subscription->payment->balance
                : $subscription->membership->price;

            $title = 'Payment Reminder — ' . $subscription->membership->name;
            $body  = 'You have an outstanding balance of UGX ' .
                     number_format($balance) .
                     '. Please complete your payment to keep your membership active.';

            $notificationService->sendToToken($user->fcm_token, $title, $body);
            $count++;
        }

        $this->info("Payment reminders sent to {$count} clients.");
    }
}