<x-becky-layout>

    <div style="margin-bottom:24px">
        <a href="{{ route('client.dashboard') }}" style="color:#555;font-size:13px;text-decoration:none">← Back to dashboard</a>
        <h2 style="color:#fff;font-size:22px;font-weight:800;margin-top:8px">
            My <span style="color:#FF6B00">Attendance</span>
        </h2>
    </div>

    <div class="bfh-card" style="margin-bottom:20px;text-align:center">
        <div class="bfh-stat-value" style="font-size:36px">{{ $grouped->flatten()->count() }}</div>
        <div class="bfh-stat-sub">Total sessions</div>
    </div>

    @if($grouped->isEmpty())
        <div class="bfh-card" style="text-align:center;padding:20px">
            <p style="color:#555;font-size:13px">No attendance recorded yet.</p>
        </div>
    @else
        @foreach($grouped as $date => $records)
            <div class="bfh-section-title" style="margin-top:16px">{{ $date }} — {{ $records->count() }} session(s)</div>
            @foreach($records as $record)
                <div class="bfh-card" style="display:flex;align-items:center;gap:12px;margin-bottom:8px">
                    <div style="width:36px;height:36px;background:#2a2a2a;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0">
                        {{ $record->session_slot === 'morning' ? '🌅' : ($record->session_slot === 'midday' ? '☀️' : '🌙') }}
                    </div>
                    <div style="flex:1">
                        <p style="color:#fff;font-size:13px;font-weight:500;text-transform:capitalize">{{ $record->session_slot }} session</p>
                        <p style="color:#555;font-size:11px;margin-top:2px">{{ \Carbon\Carbon::parse($record->attended_at)->format('h:i A') }}</p>
                        @if($record->marked_by === 'client')
                            <p style="color:#4caf50;font-size:11px;margin-top:2px">✓ Self check-in</p>
                        @else
                            <p style="color:#FF6B00;font-size:11px;margin-top:2px">✓ Marked by {{ $record->trainer->name ?? 'Trainer' }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
        @endforeach
    @endif

</x-becky-layout>