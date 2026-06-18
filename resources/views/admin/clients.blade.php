<x-becky-layout>

    <div style="margin-bottom:24px">
        <h2 style="color:#fff;font-size:22px;font-weight:800">All <span style="color:#FF6B00">Clients</span></h2>
        <p style="color:#777;font-size:13px;margin-top:4px">{{ $regularClients->count() + $corporateClients->flatten()->count() }} client(s) found</p>
    </div>

    <form method="GET" action="{{ route('admin.clients') }}" style="margin-bottom:20px">
        <div style="position:relative">
            <input type="text" name="search" value="{{ $search ?? '' }}"
                placeholder="Search by name, phone or email..."
                class="bfh-input" style="padding-left:40px">
            <span style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#555;font-size:16px">🔍</span>
            @if($search ?? false)
                <a href="{{ route('admin.clients') }}" style="position:absolute;right:14px;top:50%;transform:translateY(-50%);color:#FF6B00;font-size:12px;text-decoration:none">Clear ✕</a>
            @endif
        </div>
    </form>

    {{-- Corporate clients grouped by company --}}
    @if($corporateClients->isNotEmpty())
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px">
            <div class="bfh-section-title" style="margin-bottom:0">Corporate clients</div>
            <a href="{{ route('admin.reports.corporate') }}" style="color:#FF6B00;font-size:11px;text-decoration:none;font-weight:600">View report →</a>
        </div>
        @foreach($corporateClients as $company => $companyClients)
            <div class="bfh-card orange-border" style="margin-bottom:16px">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
                    <p style="color:#FF6B00;font-size:14px;font-weight:700">{{ $company }}</p>
                    <span class="bfh-badge active">{{ $companyClients->count() }} member(s)</span>
                </div>
                @foreach($companyClients as $client)
                    <a href="{{ route('admin.clients.show', $client->id) }}" style="text-decoration:none">
                        <div style="display:flex;align-items:center;gap:10px;padding:8px 0;border-top:0.5px solid #222">
                            <div style="width:32px;height:32px;background:#2a2a2a;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#FF6B00;font-size:11px;font-weight:700;flex-shrink:0">
                                {{ strtoupper(substr($client->name, 0, 2)) }}
                            </div>
                            <div style="flex:1">
                                <p style="color:#fff;font-size:13px;font-weight:500">{{ $client->name }}</p>
                                <p style="color:#555;font-size:11px">{{ $client->phone }}</p>
                            </div>
                            <span style="color:#444;font-size:16px">›</span>
                        </div>
                    </a>
                @endforeach
            </div>
        @endforeach
    @endif

    {{-- Regular clients --}}
    <div class="bfh-section-title">Regular clients</div>
    @if($regularClients->isEmpty())
        <div class="bfh-card" style="text-align:center;padding:32px">
            <p style="font-size:32px;margin-bottom:12px">👥</p>
            <p style="color:#555;font-size:14px">No regular clients found.</p>
        </div>
    @else
        @foreach($regularClients as $client)
            @php
                $activeSub = $client->subscriptions->whereIn('status', ['active', 'pending'])->last();
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

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
                        <div>
                            <p style="color:#666;font-size:10px;text-transform:uppercase;letter-spacing:1px">Package</p>
                            <p style="color:#aaa;font-size:12px;font-weight:600;margin-top:2px">
                                {{ $activeSub ? $activeSub->membership->name : '—' }}
                            </p>
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