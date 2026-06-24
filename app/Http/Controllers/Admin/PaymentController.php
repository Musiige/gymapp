<?php

namespace App\Http\Controllers\Admin;

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
            'amount_paid'    => ['required', 'numeric', 'min:1'],
            'payment_method' => ['required', 'in:cash,momo,airtel'],
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
                'user_id'              => $subscription->user_id,
                'amount_due'           => $amountDue,
                'amount_paid'          => $totalPaid,
                'balance'              => $balance,
                'status'               => $status,
                'payment_method'       => $request->payment_method,
                'transaction_id'       => 'CASH-' . strtoupper(Str::random(8)),
                'paid_at'              => now(),
                'marked_paid_by_admin' => true,
                'marked_by_admin_id'   => Auth::id(),
            ]
        );

 if ($status === 'paid') {
            $subscription->update(['status' => 'active', 'access_granted' => true]);
        }

        return back()->with('success', 'Payment of UGX ' . number_format($amountPaid) . ' recorded successfully.');
    }
   public function toggleAccess($subscriptionId)
    {
        $subscription = Subscription::findOrFail($subscriptionId);
        $newAccessState = !$subscription->access_granted;

        $subscription->update([
            'access_granted' => $newAccessState,
            'status' => $newAccessState ? 'active' : $subscription->status,
        ]);

        return back()->with('success', $newAccessState
            ? 'Access granted to client.'
            : 'Access revoked from client.');
    }

    public function setCustomPrice(Request $request, $subscriptionId)
    {
        $request->validate([
            'custom_price' => ['required', 'numeric', 'min:0'],
        ]);

        $subscription = Subscription::findOrFail($subscriptionId);
        $subscription->update(['custom_price' => $request->custom_price]);

        $payment = Payment::where('subscription_id', $subscriptionId)->first();
        if ($payment) {
            $newBalance = max(0, $request->custom_price - $payment->amount_paid);
            $status = 'unpaid';
            if ($payment->amount_paid >= $request->custom_price) {
                $status = 'paid';
            } elseif ($payment->amount_paid > 0) {
                $status = 'half-paid';
            }
            $payment->update([
                'amount_due' => $request->custom_price,
                'balance'    => $newBalance,
                'status'     => $status,
            ]);
            if ($status === 'paid') {
                $subscription->update(['status' => 'active', 'access_granted' => true]);
            }
        }

        return back()->with('success', 'Custom price set to UGX ' . number_format($request->custom_price) . '.');
    }
    public function voidPayment(Request $request, $subscriptionId)
{
    $subscription = Subscription::findOrFail($subscriptionId);
    $payment = Payment::where('subscription_id', $subscriptionId)->first();

    if ($payment) {
        $payment->update([
            'amount_paid'          => 0,
            'balance'              => $payment->amount_due,
            'status'               => 'unpaid',
            'marked_paid_by_admin' => true,
            'marked_by_admin_id'   => Auth::id(),
            'paid_at'              => null,
        ]);
       $subscription->update(['status' => 'pending', 'access_granted' => false]);
    }

    return back()->with('success', 'Payment voided successfully.');
}

public function editPayment(Request $request, $subscriptionId)
{
    $request->validate([
        'amount_paid' => ['required', 'numeric', 'min:0'],
    ]);

    $subscription = Subscription::with('membership')->findOrFail($subscriptionId);
    $amountDue    = $subscription->custom_price ?? $subscription->membership->price;
    $amountPaid   = (float) $request->amount_paid;
    $balance      = max(0, $amountDue - $amountPaid);

    $status = 'unpaid';
    if ($amountPaid >= $amountDue) {
        $status = 'paid';
    } elseif ($amountPaid > 0) {
        $status = 'half-paid';
    }

    Payment::updateOrCreate(
        ['subscription_id' => $subscriptionId],
        [
            'user_id'              => $subscription->user_id,
            'amount_due'           => $amountDue,
            'amount_paid'          => $amountPaid,
            'balance'              => $balance,
            'status'               => $status,
            'payment_method'       => 'cash',
            'transaction_id'       => 'EDIT-' . strtoupper(\Str::random(8)),
            'paid_at'              => now(),
            'marked_paid_by_admin' => true,
            'marked_by_admin_id'   => Auth::id(),
        ]
    );

   if ($status === 'paid') {
        $subscription->update(['status' => 'active', 'access_granted' => true]);
    } else {
        $subscription->update(['status' => 'pending', 'access_granted' => false]);
    }

    return back()->with('success', 'Payment updated to UGX ' . number_format($amountPaid) . '.');
}
}