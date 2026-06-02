<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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

    public function process(Request $request, $subscriptionId)
    {
        $request->validate([
            'payment_method' => ['required', 'in:momo,airtel'],
            'phone'          => ['required', 'string', 'min:10', 'max:15'],
            'amount'         => ['required', 'numeric', 'min:1000'],
        ]);

        $subscription = Subscription::where('id', $subscriptionId)
            ->where('user_id', Auth::id())
            ->with('membership')
            ->firstOrFail();

        $amountDue  = $subscription->membership->price;
        $amountPaid = (float) $request->amount;
        $balance    = $amountDue - $amountPaid;

        if ($balance < 0) {
            $balance = 0;
        }

        $status = 'unpaid';
        if ($amountPaid >= $amountDue) {
            $status = 'paid';
        } elseif ($amountPaid > 0) {
            $status = 'half-paid';
        }

        $payment = Payment::updateOrCreate(
            ['subscription_id' => $subscription->id],
            [
                'user_id'          => Auth::id(),
                'amount_due'       => $amountDue,
                'amount_paid'      => $amountPaid,
                'balance'          => $balance,
                'status'           => $status,
                'payment_method'   => $request->payment_method,
                'transaction_id'   => 'MOCK-' . strtoupper(Str::random(10)),
                'paid_at'          => now(),
            ]
        );

        if ($status === 'paid') {
            $subscription->update(['status' => 'active']);
        }

        return redirect()->route('client.dashboard')
            ->with('success', $this->paymentMessage($status, $balance, $amountDue));
    }

    private function paymentMessage($status, $balance, $amountDue)
    {
        return match($status) {
            'paid'      => 'Payment successful. Your membership is now active.',
            'half-paid' => 'Partial payment received. Remaining balance: UGX ' . number_format($balance) . '. Please complete payment to activate your membership.',
            default     => 'Payment not completed. Please try again.',
        };
    }
}