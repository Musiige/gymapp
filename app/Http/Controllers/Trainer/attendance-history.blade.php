<x-becky-layout>

    <div style="margin-bottom:24px">
        <a href="{{ route('trainer.dashboard') }}" style="color:#555;font-size:13px;text-decoration:none">← Back to dashboard</a>
        <h2 style="color:#fff;font-size:22px;font-weight:800;margin-top:8px">
            Attendance <span style="color:#FF6B00">History</span>
        </h2>
    </div>

    {{-- Filter tabs --}}
    <div style="display:flex;gap:6px;margin-bottom:20px">
        @foreach(['today' => 'Today', 'week' => 'This Week', 'month' => 'This Month'] as $val => $label)
            <a href="?filter={{ $val }}"
               style="padding:6px 14px;border-radius:20px;font-size:12px;font-weight:700;text-decoration:none;
               {{ $filter === $val ? 'background:#FF6B00;color:#fff' : 'background:#1e1e1e;color:#666;border:0.5px solid #333' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="bfh-card" style="margin-bottom:20px;text-align:center">
        <div class="bfh-stat-value" style="font-size:36px">{{ $grouped->flatten()->count() }}</div>
        <div class="bfh-stat-sub">Total check-ins</div>
    </div>

    @if($grouped->isEmpty())
        <div class="bfh-card" style="text-align:center;padding:20px">
            <p style="color:#555;font-size:13px">No attendance recorded for this period.</p>
        </div>
    @else
        @foreach($grouped as $date => $records)
            <div class="bfh-section-title" style="margin-top:16px">
                {{ $date }} — {{ $records->count() }} check-in(s)
            </div>
            @foreach($records as $record)
                <div class="bfh-card" style="display:flex;align-items:center;gap:12px;margin-bottom:10px">
                    <div style="width:38px;height:38px;background:#2a2a2a;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#FF6B00;font-size:12px;font-weight:700;flex-shrink:0">
                        {{ strtoupper(substr($record->client->name, 0, 2)) }}
                    </div>
                    <div style="flex:1">
                        <p style="color:#fff;font-size:13px;font-weight:500">{{ $record->client->name }}</p>
                        <p style="color:#555;font-size:11px;margin-top:2px">{{ \Carbon\Carbon::parse($record->attended_at)->format('h:i A') }}</p>
                    </div>
                    <span class="bfh-badge active" style="text-transform:capitalize">{{ $record->session_slot }}</span>
                </div>
            @endforeach
        @endforeach
    @endif

</x-becky-layout>