<x-becky-layout>

    <div style="margin-bottom:24px">
        <a href="{{ route('admin.dashboard') }}" style="color:#555;font-size:13px;text-decoration:none">← Back to dashboard</a>
        <h2 style="color:#fff;font-size:22px;font-weight:800;margin-top:8px">
            {{ ucfirst($slot) }} <span style="color:#FF6B00">Session</span>
        </h2>
        <p style="color:#777;font-size:13px;margin-top:4px">
            {{ $slot === 'morning' ? '5:30 – 8:00 AM' : ($slot === 'midday' ? '8:00 AM – 3:30 PM' : '3:30 – 9:00 PM') }}
        </p>
    </div>

    {{-- Filter tabs --}}
    <div style="display:flex;gap:6px;margin-bottom:20px">
        @foreach(['today' => 'Today', 'week' => 'This Week', 'month' => 'This Month'] as $val => $label)
            <a href="?slot={{ $slot }}&filter={{ $val }}"
               style="padding:6px 14px;border-radius:20px;font-size:12px;font-weight:700;text-decoration:none;
               {{ $filter === $val ? 'background:#FF6B00;color:#fff' : 'background:#1e1e1e;color:#666;border:0.5px solid #333' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- Summary --}}
    <div class="bfh-card" style="margin-bottom:20px;text-align:center">
        <div class="bfh-stat-value" style="font-size:36px">{{ $records->count() }}</div>
        <div class="bfh-stat-sub">Total check-ins</div>
    </div>

    {{-- Attendance list --}}
    @if($records->isEmpty())
        <div class="bfh-card" style="text-align:center;padding:20px">
            <p style="color:#555;font-size:13px">No attendance recorded for this session.</p>
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