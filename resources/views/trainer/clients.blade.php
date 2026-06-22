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
            <a href="{{ route('trainer.clients.show', $client->id) }}" style="text-decoration:none;display:block;margin-bottom:12px">
                <div class="bfh-card">
                    <div style="display:flex;align-items:center;gap:14px">
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
                </div>
            </a>
        @endforeach
    @endif

</x-becky-layout>