<x-becky-layout>

    <div style="margin-bottom:24px">
        <a href="{{ route('admin.reports.corporate') }}?month={{ $month }}" style="color:#555;font-size:13px;text-decoration:none">← Back to corporate report</a>
        <h2 style="color:#fff;font-size:22px;font-weight:800;margin-top:8px">
            {{ $company }}
        </h2>
        <p style="color:#777;font-size:13px;margin-top:4px">
            {{ ucfirst($period) }}{{ $slot ? ' · ' . ucfirst($slot) . ' session' : '' }}
        </p>
    </div>

    <div class="bfh-card" style="margin-bottom:20px;text-align:center">
        <div class="bfh-stat-value" style="font-size:36px">{{ $records->count() }}</div>
        <div class="bfh-stat-sub">Total check-ins</div>
    </div>

    @if($records->isEmpty())
        <div class="bfh-card" style="text-align:center;padding:20px">
            <p style="color:#555;font-size:13px">No attendance found for this selection.</p>
        </div>
    @else
        @foreach($records as $record)
            <a href="{{ route('admin.clients.show', $record->client->id) }}" style="text-decoration:none">
                <div class="bfh-card" style="display:flex;align-items:center;gap:12px;margin-bottom:10px">
                    <div style="width:38px;height:38px;background:#2a2a2a;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#FF6B00;font-size:12px;font-weight:700;flex-shrink:0">
                        {{ strtoupper(substr($record->client->name, 0, 2)) }}
                    </div>
                    <div style="flex:1">
                        <p style="color:#fff;font-size:13px;font-weight:500">{{ $record->client->name }}</p>
                        <p style="color:#555;font-size:11px;margin-top:2px">{{ \Carbon\Carbon::parse($record->attended_at)->format('d M Y · h:i A') }}</p>
                    </div>
                    <span class="bfh-badge active" style="text-transform:capitalize">{{ $record->session_slot }}</span>
                </div>
            </a>
        @endforeach
    @endif

</x-becky-layout>