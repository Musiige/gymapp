<x-becky-layout>

    <div style="margin-bottom:24px">
        <a href="{{ route('admin.dashboard') }}" style="color:#555;font-size:13px;text-decoration:none">← Back to dashboard</a>
        <h2 style="color:#fff;font-size:22px;font-weight:800;margin-top:8px">
            Payment Status <span style="color:#FF6B00">Report</span>
        </h2>
    </div>

    {{-- Filter tabs --}}
    <div style="display:flex;gap:6px;margin-bottom:20px">
        @foreach(['paid' => 'Paid', 'half-paid' => 'Half Paid', 'unpaid' => 'Unpaid'] as $val => $label)
            <a href="?status={{ $val }}"
               style="padding:6px 14px;border-radius:20px;font-size:12px;font-weight:700;text-decoration:none;
               {{ $status === $val ? 'background:#FF6B00;color:#fff' : 'background:#1e1e1e;color:#666;border:0.5px solid #333' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- Summary --}}
    <div class="bfh-card" style="margin-bottom:20px;text-align:center">
        <div class="bfh-stat-value" style="font-size:36px;
            {{ $status === 'paid' ? 'color:#4caf50' : ($status === 'half-paid' ? 'color:#FF6B00' : 'color:#ff4444') }}">
            {{ $payments->count() }}
        </div>
        <div class="bfh-stat-sub">{{ ucfirst($status) }} clients</div>
    </div>

    {{-- List --}}
    @if($payments->isEmpty())
        <div class="bfh-card" style="text-align:center;padding:20px">
            <p style="color:#555;font-size:13px">No clients with {{ $status }} status.</p>
        </div>
    @else
        @foreach($payments as $payment)
            <a href="{{ route('admin.clients.show', $payment->subscription->user->id) }}" style="text-decoration:none">
                <div class="bfh-card" style="display:flex;align-items:center;gap:12px;margin-bottom:10px">
                    <div style="width:38px;height:38px;background:#2a2a2a;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#FF6B00;font-size:12px;font-weight:700;flex-shrink:0">
                        {{ strtoupper(substr($payment->subscription->user->name, 0, 2)) }}
                    </div>
                    <div style="flex:1">
                        <p style="color:#fff;font-size:13px;font-weight:500">{{ $payment->subscription->user->name }}</p>
                        <p style="color:#555;font-size:11px;margin-top:2px">{{ $payment->subscription->membership->name }}</p>
                    </div>
                    <div style="text-align:right">
                        <p style="color:#4caf50;font-size:13px;font-weight:700">UGX {{ number_format($payment->amount_paid) }}</p>
                        @if($payment->balance > 0)
                            <p style="color:#FF6B00;font-size:11px;margin-top:2px">Balance: UGX {{ number_format($payment->balance) }}</p>
                        @endif
                        <span class="bfh-badge {{ $payment->status }}" style="margin-top:4px">{{ $payment->status }}</span>
                    </div>
                </div>
            </a>
        @endforeach
    @endif

</x-becky-layout>