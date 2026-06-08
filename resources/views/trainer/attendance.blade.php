<x-becky-layout>

    <div style="margin-bottom:24px">
        <h2 style="color:#fff;font-size:22px;font-weight:800">Mark <span style="color:#FF6B00">Attendance</span></h2>
        <p style="color:#777;font-size:13px;margin-top:4px">{{ now()->format('l, d M Y') }}</p>
    </div>

    <div class="bfh-section-title">Record attendance</div>
    <div class="bfh-card" style="margin-bottom:20px">
        <form method="POST" action="{{ route('trainer.attendance.store') }}">
            @csrf
            <div class="bfh-form-group">
                <label class="bfh-form-label">Client</label>
                <input type="text"
    placeholder="Search client..."
    oninput="filterSelect(this, 'attendance-client-select')"
    class="bfh-input" style="margin-bottom:6px">
<select name="client_id" id="attendance-client-select" class="bfh-select">
    <option value="">Select client</option>
    @foreach($clients as $client)
        <option value="{{ $client->id }}">{{ $client->name }} — {{ $client->phone }}</option>
    @endforeach
</select>
                @error('client_id')<p class="bfh-error">{{ $message }}</p>@enderror
            </div>
            <div class="bfh-form-group" style="margin-bottom:0">
                <label class="bfh-form-label">Session</label>
                <select name="session_slot" class="bfh-select">
                    <option value="">Select session</option>
                    <option value="morning">🌅 Morning (5:30am – 8:00am)</option>
                    <option value="midday">☀️ Midday (8:00am – 3:30pm)</option>
                    <option value="evening">🌙 Evening (3:30pm – 9:00pm)</option>
                </select>
                @error('session_slot')<p class="bfh-error">{{ $message }}</p>@enderror
            </div>
            <div class="bfh-divider"></div>
            <button type="submit" class="bfh-btn">Mark Attendance</button>
        </form>
    </div>

    <div class="bfh-section-title">Today's attendance ({{ $todayAttendance->count() }})</div>

    @if($todayAttendance->isEmpty())
        <div class="bfh-card" style="text-align:center;padding:24px">
            <p style="color:#555;font-size:13px">No attendance recorded today yet.</p>
        </div>
    @else
       @foreach($todayAttendance as $record)
    <div class="bfh-card" style="display:flex;align-items:center;gap:14px;margin-bottom:10px">
        <div style="width:40px;height:40px;background:#2a2a2a;border:0.5px solid #3a3a3a;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#FF6B00;font-size:13px;font-weight:700;flex-shrink:0">
            {{ strtoupper(substr($record->client->name, 0, 2)) }}
        </div>
        <div style="flex:1">
            <p style="color:#fff;font-size:14px;font-weight:500">{{ $record->client->name }}</p>
            <p style="color:#555;font-size:12px;margin-top:2px">{{ \Carbon\Carbon::parse($record->attended_at)->format('h:i A') }}</p>
        </div>
        <div style="text-align:right">
            <span class="bfh-badge active" style="text-transform:capitalize">{{ $record->session_slot }}</span>
            <p style="font-size:10px;margin-top:4px;{{ $record->marked_by === 'client' ? 'color:#4caf50' : 'color:#FF6B00' }}">
                {{ $record->marked_by === 'client' ? '✓ Self check-in' : '✓ Trainer marked' }}
            </p>
        </div>
    </div>
@endforeach
    @endif

</x-becky-layout>