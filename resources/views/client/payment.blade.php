<x-becky-layout>

    <div style="margin-bottom:24px">
        <h2 style="color:#fff;font-size:22px;font-weight:800">Membership <span style="color:#FF6B00">Payment</span></h2>
        <p style="color:#777;font-size:13px;margin-top:4px">Pay at the front desk to activate your access</p>
    </div>

    @if(session('success'))
        <div style="background:#0a1a0a;border:0.5px solid #1a3a1a;border-radius:12px;padding:12px 16px;margin-bottom:16px">
            <p style="color:#4ade80;font-size:13px">{{ session('success') }}</p>
        </div>
    @endif
    @if(session('error'))
        <div style="background:#1a0a0a;border:0.5px solid #3a0a0a;border-radius:12px;padding:12px 16px;margin-bottom:16px">
            <p style="color:#ff4444;font-size:13px">{{ session('error') }}</p>
        </div>
    @endif

    <div class="bfh-section-title">Package summary</div>
    <div class="bfh-card orange-border" style="margin-bottom:20px">
        <div class="bfh-row">
            <div>
                <p style="color:#fff;font-size:15px;font-weight:600">{{ $subscription->membership->name }}</p>
                <p style="color:#FF6B00;font-size:18px;font-weight:700;margin-top:6px">UGX {{ number_format($subscription->membership->price) }}</p>
            </div>
            @if($payment)
                <div style="text-align:right">
                    <span class="bfh-badge {{ $payment->status }}">{{ $payment->status }}</span>
                    <p style="color:#555;font-size:11px;margin-top:6px">Paid: UGX {{ number_format($payment->amount_paid) }}</p>
                    <p style="color:#ff4444;font-size:11px;margin-top:2px">Balance: UGX {{ number_format($payment->balance) }}</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Disclaimer --}}
    <div style="background:#1a1a0a;border:0.5px solid #3a3a0a;border-radius:12px;padding:14px 16px;margin-bottom:20px">
        <p style="color:#FF6B00;font-size:12px;font-weight:700;margin-bottom:6px">⚠️ Important Notice</p>
        <p style="color:#888;font-size:12px;line-height:1.6">
            Once a package is purchased, time is not transferred to the following weeks even if you miss sessions while the gym was open. All packages run for their full duration from the start date regardless of attendance.
        </p>
    </div>

    @if(!$payment || $payment->status !== 'paid')

       <div class="bfh-card" style="text-align:center;padding:32px 16px;margin-bottom:16px">
            <p style="font-size:40px;margin-bottom:12px">💵</p>
            <p style="color:#fff;font-size:16px;font-weight:700">Pay at the Front Desk</p>
            <p style="color:#777;font-size:13px;margin-top:10px;line-height:1.6;text-align:left">
                Please complete your payment by:
            </p>
            <div style="text-align:left;margin-top:10px">
                <p style="color:#aaa;font-size:13px;margin-bottom:8px">💰 Cash — directly to your trainer or admin</p>
                <p style="color:#aaa;font-size:13px;margin-bottom:8px">📱 MTN MoMo Pay — Merchant Code <strong style="color:#FF6B00">288560</strong> (*165*3#)</p>
                <p style="color:#aaa;font-size:13px">📲 Airtel Pay — Merchant Code <strong style="color:#FF6B00">6855633</strong> (*185*9#)</p>
            </div>
            <p style="color:#555;font-size:12px;margin-top:16px;line-height:1.6">
                Once payment is received, your trainer or admin will confirm it and activate your gym access immediately.
            </p>
        </div>

        {{-- Cancel button --}}
        <form method="POST" action="{{ route('client.subscription.cancel', $subscription->id) }}">
            @csrf
            @method('DELETE')
            <button type="submit" class="bfh-btn danger"
                onclick="return confirm('Are you sure you want to cancel this subscription? This cannot be undone.')">
                Cancel Subscription
            </button>
        </form>

    @else
        <div class="bfh-card" style="text-align:center;padding:32px 16px">
            <p style="font-size:40px;margin-bottom:12px">✅</p>
            <p style="color:#fff;font-size:18px;font-weight:700">Payment Complete</p>
            <p style="color:#777;font-size:13px;margin-top:6px">Your membership is fully paid and active.</p>
            <a href="{{ route('client.dashboard') }}" class="bfh-btn" style="margin-top:20px">Back to Dashboard</a>
        </div>
    @endif

</x-becky-layout>