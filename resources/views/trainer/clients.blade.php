<x-becky-layout>

    <div style="margin-bottom:24px">
        <h2 style="color:#fff;font-size:22px;font-weight:800">My <span style="color:#FF6B00">Clients</span></h2>
        <p style="color:#777;font-size:13px;margin-top:4px">{{ $clients->count() }} registered client(s)</p>
    </div>

<form method="GET" action="{{ route('trainer.clients') }}" style="margin-bottom:20px">
    <div style="position:relative">
        <input type="text" name="search" value="{{ $search ?? '' }}"
            placeholder="Search by name or phone..."
            class="bfh-input" style="padding-left:40px">
        <span style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#555;font-size:16px">🔍</span>
        @if($search)
            <a href="{{ route('trainer.clients') }}" style="position:absolute;right:14px;top:50%;transform:translateY(-50%);color:#FF6B00;font-size:12px;text-decoration:none">Clear ✕</a>
        @endif
    </div>
</form>

    @if($clients->isEmpty())
        <div class="bfh-card" style="text-align:center;padding:32px">
            <p style="font-size:32px;margin-bottom:12px">👥</p>
            <p style="color:#555;font-size:14px">No clients registered yet.</p>
        </div>
    @else
       @foreach($clients as $client)
    @php
        $sub = $client->subscriptions->sortByDesc('created_at')->first();
    @endphp
    <div class="bfh-card" style="margin-bottom:12px">
        <div style="display:flex;align-items:center;gap:14px;margin-bottom:12px">
            <div style="width:44px;height:44px;background:#2a2a2a;border:0.5px solid #3a3a3a;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#FF6B00;font-size:15px;font-weight:700;flex-shrink:0">
                {{ strtoupper(substr($client->name, 0, 2)) }}
            </div>
            <div style="flex:1;min-width:0">
                <p style="color:#fff;font-size:14px;font-weight:600">{{ $client->name }}</p>
                <p style="color:#555;font-size:12px;margin-top:2px">{{ $client->phone }}</p>
            </div>
            <div style="text-align:right;flex-shrink:0">
                @if($sub)
                    <span class="bfh-badge {{ $sub->status }}">{{ $sub->status }}</span>
                    <p style="color:#555;font-size:11px;margin-top:4px">{{ $sub->membership->name }}</p>
                @else
                    <span class="bfh-badge expired">No package</span>
                @endif
            </div>
        </div>

        @if($sub && in_array($sub->status, ['active', 'pending']) && (!$sub->payment || $sub->payment->status !== 'paid'))
            <div style="background:#0a0a0a;border-radius:10px;padding:12px">
                <p style="color:#FF6B00;font-size:11px;text-transform:uppercase;letter-spacing:1px;margin-bottom:8px">
                    Record cash payment
                    <span style="color:#555;font-size:10px;margin-left:6px">
                        Balance: UGX {{ number_format($sub->payment->balance ?? ($sub->custom_price ?? $sub->membership->price)) }}
                    </span>
                </p>
                <form method="POST" action="{{ route('trainer.payment.mark', $sub->id) }}">
                    @csrf
                    <div style="display:flex;gap:8px">
                        <input type="number" name="amount_paid"
                            placeholder="Amount (UGX)"
                            value="{{ $sub->payment ? $sub->payment->balance : ($sub->custom_price ?? $sub->membership->price) }}"
                            min="1"
                            class="bfh-input" style="flex:1;padding:10px 12px">
                        <button type="submit" class="bfh-btn sm" style="width:auto;padding:10px 16px;white-space:nowrap">
                            Record
                        </button>
                    </div>
                </form>
            </div>
        @elseif($sub && $sub->payment && $sub->payment->status === 'paid')
            <div style="background:#1a3a1a;border-radius:10px;padding:10px;text-align:center">
                <p style="color:#4caf50;font-size:12px;font-weight:600">✓ Fully paid — UGX {{ number_format($sub->payment->amount_paid) }}</p>
            </div>
        @endif
    </div>
@endforeach
    @endif

</x-becky-layout>