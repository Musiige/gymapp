<x-becky-layout>

    <div style="margin-bottom:24px">
        <h2 style="color:#fff;font-size:22px;font-weight:800">All <span style="color:#FF6B00">Clients</span></h2>
        <p style="color:#777;font-size:13px;margin-top:4px">{{ $clients->count() }} client(s) found</p>
    </div>

    {{-- Search --}}
    <form method="GET" action="{{ route('admin.clients') }}" style="margin-bottom:20px">
        <div style="position:relative">
            <input type="text" name="search" value="{{ $search }}"
                placeholder="Search by name, phone or email..."
                class="bfh-input" style="padding-left:40px">
            <span style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#555;font-size:16px">🔍</span>
        </div>
    </form>

    @if($clients->isEmpty())
        <div class="bfh-card" style="text-align:center;padding:32px">
            <p style="font-size:32px;margin-bottom:12px">👥</p>
            <p style="color:#555;font-size:14px">No clients found.</p>
        </div>
    @else
        @foreach($clients as $client)
            @php
                $activeSub = $client->subscriptions->whereIn('status', ['active', 'pending'])->last();
                $totalPaid = $client->subscriptions->sum(fn($s) => $s->payment->amount_paid ?? 0);
            @endphp
            <a href="{{ route('admin.clients.show', $client->id) }}" style="text-decoration:none;display:block;margin-bottom:12px">
                <div class="bfh-card {{ $activeSub ? 'orange-border' : '' }}" style="transition:border-color 0.2s">
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px">
                        <div style="width:44px;height:44px;background:#2a2a2a;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#FF6B00;font-size:15px;font-weight:700;flex-shrink:0">
                            {{ strtoupper(substr($client->name, 0, 2)) }}
                        </div>
                        <div style="flex:1">
                            <p style="color:#fff;font-size:15px;font-weight:600">{{ $client->name }}</p>
                            <p style="color:#555;font-size:12px;margin-top:2px">{{ $client->phone }}</p>
                        </div>
                        @if($activeSub)
                            <span class="bfh-badge {{ $activeSub->status }}">{{ $activeSub->status }}</span>
                        @else
                            <span class="bfh-badge expired">Inactive</span>
                        @endif
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px">
                        <div>
                            <p style="color:#666;font-size:10px;text-transform:uppercase;letter-spacing:1px">Package</p>
                            <p style="color:#aaa;font-size:12px;font-weight:600;margin-top:2px">
                                {{ $activeSub ? $activeSub->membership->name : '—' }}
                            </p>
                        </div>
                        <div>
                            <p style="color:#666;font-size:10px;text-transform:uppercase;letter-spacing:1px">Total paid</p>
                            <p style="color:#4caf50;font-size:12px;font-weight:600;margin-top:2px">UGX {{ number_format($totalPaid) }}</p>
                        </div>
                        <div>
                            <p style="color:#666;font-size:10px;text-transform:uppercase;letter-spacing:1px">Subscriptions</p>
                            <p style="color:#aaa;font-size:12px;font-weight:600;margin-top:2px">{{ $client->subscriptions->count() }}</p>
                        </div>
                    </div>

                    <div style="margin-top:10px;text-align:right">
                        <span style="color:#FF6B00;font-size:12px">View full history →</span>
                    </div>
                </div>
            </a>
        @endforeach
    @endif

</x-becky-layout>