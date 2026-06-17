<x-becky-layout>

    <div style="margin-bottom:24px">
        <h2 style="color:#fff;font-size:22px;font-weight:800">Complete <span style="color:#FF6B00">Payment</span></h2>
        <p style="color:#777;font-size:13px;margin-top:4px">Secure mobile money payment</p>
    </div>

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

        <div class="bfh-section-title">Payment method</div>
        <form method="POST" action="{{ route('client.payment.process', $subscription->id) }}">
            @csrf

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:16px">
                <label style="cursor:pointer">
                    <input type="radio" name="payment_method" value="momo" required style="display:none" id="momo">
                    <div onclick="selectMethod('momo')" id="momo-card" class="bfh-card" style="margin-bottom:0;text-align:center;border-color:#2e2e2e;transition:border-color 0.2s">
                        <p style="font-size:22px;margin-bottom:6px">📱</p>
                        <p style="color:#fff;font-size:13px;font-weight:600">MTN MoMo</p>
                        <p style="color:#555;font-size:11px;margin-top:2px">Mobile Money</p>
                    </div>
                </label>
                <label style="cursor:pointer">
                    <input type="radio" name="payment_method" value="airtel" style="display:none" id="airtel">
                    <div onclick="selectMethod('airtel')" id="airtel-card" class="bfh-card" style="margin-bottom:0;text-align:center;border-color:#2e2e2e;transition:border-color 0.2s">
                        <p style="font-size:22px;margin-bottom:6px">📲</p>
                        <p style="color:#fff;font-size:13px;font-weight:600">Airtel Pay</p>
                        <p style="color:#555;font-size:11px;margin-top:2px">Airtel Money</p>
                    </div>
                </label>
            </div>
            @error('payment_method')<p class="bfh-error" style="margin-bottom:12px">{{ $message }}</p>@enderror

            <div class="bfh-form-group">
                <label class="bfh-form-label">Mobile money number</label>
                <input type="text" name="phone" value="{{ old('phone', Auth::user()->phone) }}"
                    placeholder="e.g. 0771234567" class="bfh-input">
                @error('phone')<p class="bfh-error">{{ $message }}</p>@enderror
            </div>

            <div class="bfh-form-group">
                <label class="bfh-form-label">Amount to pay (UGX)</label>
                <input type="number" name="amount"
                    value="{{ old('amount', $payment ? $payment->balance : $subscription->membership->price) }}"
                    min="1000"
                    max="{{ $subscription->membership->price }}"
                    class="bfh-input">
                <p style="color:#555;font-size:11px;margin-top:6px">Full amount: UGX {{ number_format($subscription->membership->price) }}. You can pay partially.</p>
                @error('amount')<p class="bfh-error">{{ $message }}</p>@enderror
            </div>

            <button type="submit" class="bfh-btn" style="margin-bottom:12px">Pay Now</button>
        </form>

        {{-- Cancel button --}}
        <form method="POST" action="{{ route('client.subscription.cancel', $subscription->id) }}">
            @csrf
            @method('DELETE')
            <button type="submit" class="bfh-btn danger"
                onclick="return confirm('Are you sure you want to cancel this subscription? This cannot be undone.')">
                Cancel Subscription
            </button>
        </form>

        <script>
            function selectMethod(method) {
                document.getElementById('momo-card').style.borderColor = method === 'momo' ? '#FF6B00' : '#2e2e2e';
                document.getElementById('airtel-card').style.borderColor = method === 'airtel' ? '#FF6B00' : '#2e2e2e';
                document.getElementById(method).checked = true;
            }
        </script>

    @else
        <div class="bfh-card" style="text-align:center;padding:32px 16px">
            <p style="font-size:40px;margin-bottom:12px">✅</p>
            <p style="color:#fff;font-size:18px;font-weight:700">Payment Complete</p>
            <p style="color:#777;font-size:13px;margin-top:6px">Your membership is fully paid and active.</p>
            <a href="{{ route('client.dashboard') }}" class="bfh-btn" style="margin-top:20px">Back to Dashboard</a>
        </div>
    @endif

</x-becky-layout>