<x-becky-layout>

    <div style="margin-bottom:24px">
        <a href="{{ route('client.dashboard') }}" style="color:#555;font-size:13px;text-decoration:none">← Back to dashboard</a>
        <h2 style="color:#fff;font-size:22px;font-weight:800;margin-top:8px">
            Payment <span style="color:#FF6B00">History</span>
        </h2>
    </div>

    @if($subscriptions->isEmpty())
        <div class="bfh-card" style="text-align:center;padding:20px">
            <p style="color:#555;font-size:13px">No payment history yet.</p>
        </div>
    @else
        @foreach($subscriptions as $subscription)
            @php
                $payment = $subscription->payment;
                $due = $payment->amount_due ?? $subscription->membership?->price ?? 0;
                $paid = $payment->amount_paid ?? 0;
                $status = $payment->status ?? 'pending';
            @endphp
            <div class="bfh-card" style="margin-bottom:10px">
                <div class="bfh-row">
                    <div>
                        <p style="color:#fff;font-size:14px;font-weight:600">{{ $subscription->membership->name ?? 'Membership' }}</p>
                        <p style="color:#555;font-size:11px;margin-top:2px">{{ \Carbon\Carbon::parse($subscription->start_date)->format('d M Y') }}</p>
                    </div>
                    <span class="bfh-badge {{ $status }}">{{ $status }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;margin-top:10px">
                    <span style="color:#555;font-size:11px">Due: UGX {{ number_format($due) }}</span>
                    <span style="color:#FF6B00;font-size:11px">Paid: UGX {{ number_format($paid) }}</span>
                </div>
                @if($payment?->payment_method)
                    <p style="color:#444;font-size:11px;margin-top:6px;text-transform:uppercase">{{ $payment->payment_method }}</p>
                @endif
            </div>
        @endforeach
    @endif

</x-becky-layout>