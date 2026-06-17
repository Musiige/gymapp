<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function markPaid(Request $request, $subscriptionId)
    {
        $request->validate([
            'amount_paid' => ['required', 'numeric', 'min:1'],
        ]);

        $subscription = Subscription::with('membership')->findOrFail($subscriptionId);

        $amountDue  = $subscription->custom_price ?? $subscription->membership->price;
        $amountPaid = (float) $request->amount_paid;
        $existing   = Payment::where('subscription_id', $subscriptionId)->first();

        $totalPaid = ($existing ? $existing->amount_paid : 0) + $amountPaid;
        $balance   = max(0, $amountDue - $totalPaid);

        $status = 'unpaid';
        if ($totalPaid >= $amountDue) {
            $status = 'paid';
        } elseif ($totalPaid > 0) {
            $status = 'half-paid';
        }

        Payment::updateOrCreate(
            ['subscription_id' => $subscriptionId],
            [
                'user_id'               => $subscription->user_id,
                'amount_due'            => $amountDue,
                'amount_paid'           => $totalPaid,
                'balance'               => $balance,
                'status'                => $status,
                'payment_method'        => 'cash',
                'transaction_id'        => 'TRAINER-' . strtoupper(Str::random(8)),
                'paid_at'               => now(),
                'marked_paid_by_admin'  => false,
                'marked_by_trainer_id'  => Auth::id(),
            ]
        );

        if ($status === 'paid') {
            $subscription->update(['status' => 'active']);
        }

        return back()->with('success', 'Payment of UGX ' . number_format($amountPaid) . ' recorded.');
    }
}