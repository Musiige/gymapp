<x-becky-layout>

    <div style="margin-bottom:24px">
        <h2 style="color:#fff;font-size:22px;font-weight:800">My <span style="color:#FF6B00">Clients</span></h2>
        <p style="color:#777;font-size:13px;margin-top:4px">{{ $regularClients->count() + $corporateClients->flatten()->count() }} registered client(s)</p>
    </div>

    <div style="position:relative;margin-bottom:20px">
        <input type="text" id="client-filter-input"
            placeholder="Search by name or phone..."
            class="bfh-input" style="padding-left:40px" autocomplete="off">
        <span style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#555;font-size:16px">🔍</span>
    </div>

    {{-- Corporate clients grouped by company --}}
    @if($corporateClients->isNotEmpty())
        <div class="bfh-section-title">Corporate clients</div>
        @foreach($corporateClients as $company => $companyClients)
            <div class="bfh-card orange-border" style="margin-bottom:16px">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
                    <p style="color:#FF6B00;font-size:14px;font-weight:700">{{ $company }}</p>
                    <span class="bfh-badge active">{{ $companyClients->count() }} member(s)</span>
                </div>
                @foreach($companyClients as $client)
                    <a href="{{ route('trainer.clients.show', $client->id) }}" style="text-decoration:none"
                        class="client-row" data-search="{{ strtolower($client->name . ' ' . $client->phone) }}">
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
                $sub = $client->subscriptions->sortByDesc('created_at')->first();
            @endphp
            <a href="{{ route('trainer.clients.show', $client->id) }}" style="text-decoration:none;display:block;margin-bottom:12px"
                class="client-row" data-search="{{ strtolower($client->name . ' ' . $client->phone) }}">
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

    <div id="no-results" style="display:none;text-align:center;padding:32px">
        <p style="color:#555;font-size:14px">No clients match your search.</p>
    </div>

    <script>
        const filterInput = document.getElementById('client-filter-input');
        const rows = document.querySelectorAll('.client-row');
        const noResults = document.getElementById('no-results');

        filterInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            let visibleCount = 0;

            rows.forEach(row => {
                if (row.dataset.search.includes(query)) {
                    row.style.display = 'block';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            noResults.style.display = visibleCount === 0 ? 'block' : 'none';
        });
    </script>

</x-becky-layout>