<x-becky-layout>

    <div style="margin-bottom:24px">
        <a href="{{ route('admin.clients.show', $client->id) }}" style="color:#555;font-size:13px;text-decoration:none">← Back to {{ $client->name }}</a>
        <h2 style="color:#fff;font-size:22px;font-weight:800;margin-top:8px">
            Subscription <span style="color:#FF6B00">History</span>
        </h2>
    </div>

    @if($subscriptions->isEmpty())
        <div class="bfh-card" style="text-align:center;padding:20px">
            <p style="color:#555;font-size:13px">No subscriptions yet.</p>
        </div>
    @else
        @foreach($subscriptions as $sub)
            <div class="bfh-card" style="margin-bottom:10px">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px">
                    <p style="color:#fff;font-size:14px;font-weight:600">{{ $sub->membership->name }}</p>
                    <span class="bfh-badge {{ $sub->status }}">{{ $sub->status }}</span>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
                    <div>
                        <p style="color:#666;font-size:10px;text-transform:uppercase;letter-spacing:1px">Total due</p>
                        <p style="color:#fff;font-size:13px;font-weight:600;margin-top:2px">UGX {{ number_format($sub->custom_price ?? $sub->membership->price) }}</p>
                    </div>
                    <div>
                        <p style="color:#666;font-size:10px;text-transform:uppercase;letter-spacing:1px">Paid</p>
                        <p style="color:#4caf50;font-size:13px;font-weight:600;margin-top:2px">UGX {{ number_format($sub->payment->amount_paid ?? 0) }}</p>
                    </div>
                </div>
                <div style="margin-top:10px;padding-top:10px;border-top:0.5px solid #222">
                    <p style="color:#555;font-size:11px">
                        {{ \Carbon\Carbon::parse($sub->start_date)->format('d M Y') }} → {{ \Carbon\Carbon::parse($sub->end_date)->format('d M Y') }}
                    </p>
                </div>
            </div>
        @endforeach
    @endif

</x-becky-layout>