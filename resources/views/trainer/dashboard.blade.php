<x-becky-layout>

    <div style="margin-bottom:24px">
        <p style="color:#777;font-size:13px">Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }} 💪</p>
        <h2 style="color:#fff;font-size:22px;font-weight:800;margin-top:4px">
            Hey, <span style="color:#FF6B00">{{ explode(' ', Auth::user()->name)[0] }}</span>
        </h2>
        <p style="color:#555;font-size:13px;margin-top:4px">{{ now()->format('l, d M Y') }}</p>
    </div>

    @php
        $totalClients = \App\Models\User::where('role', 'client')->count();
        $todayCount = \App\Models\Attendance::where('trainer_id', Auth::id())
            ->whereDate('attended_at', today())
            ->count();
        $totalWorkouts = \App\Models\Workout::where('trainer_id', Auth::id())->count();
        $monthCount = \App\Models\Attendance::where('trainer_id', Auth::id())
            ->whereMonth('attended_at', now()->month)
            ->count();
    @endphp

    <div class="bfh-stat-grid">
        <div class="bfh-stat">
            <div class="bfh-stat-label">Clients</div>
            <div class="bfh-stat-value">{{ $totalClients }}</div>
            <div class="bfh-stat-sub">Registered</div>
        </div>
        <div class="bfh-stat">
            <div class="bfh-stat-label">Today</div>
            <div class="bfh-stat-value">{{ $todayCount }}</div>
            <div class="bfh-stat-sub">Attended</div>
        </div>
        <div class="bfh-stat">
            <div class="bfh-stat-label">This month</div>
            <div class="bfh-stat-value">{{ $monthCount }}</div>
            <div class="bfh-stat-sub">Sessions</div>
        </div>
        <div class="bfh-stat">
            <div class="bfh-stat-label">Workouts</div>
            <div class="bfh-stat-value">{{ $totalWorkouts }}</div>
            <div class="bfh-stat-sub">Created</div>
        </div>
    </div>

    <div class="bfh-section-title">Quick actions</div>

    <a href="{{ route('trainer.clients') }}" style="text-decoration:none;display:block;margin-bottom:12px">
        <div class="bfh-card" style="display:flex;align-items:center;gap:14px">
            <div class="bfh-icon-box">👥</div>
            <div style="flex:1">
                <p style="color:#fff;font-size:15px;font-weight:600">My Clients</p>
                <p style="color:#555;font-size:12px;margin-top:2px">{{ $totalClients }} registered client(s)</p>
            </div>
            <span style="color:#444;font-size:20px">›</span>
        </div>
    </a>

    <a href="{{ route('trainer.attendance') }}" style="text-decoration:none;display:block;margin-bottom:12px">
        <div class="bfh-card" style="display:flex;align-items:center;gap:14px">
            <div class="bfh-icon-box">✅</div>
            <div style="flex:1">
                <p style="color:#fff;font-size:15px;font-weight:600">Mark Attendance</p>
                <p style="color:#555;font-size:12px;margin-top:2px">{{ $todayCount }} marked today</p>
            </div>
            <span style="color:#444;font-size:20px">›</span>
        </div>
    </a>

    <a href="{{ route('trainer.workouts') }}" style="text-decoration:none;display:block;margin-bottom:12px">
        <div class="bfh-card" style="display:flex;align-items:center;gap:14px">
            <div class="bfh-icon-box">💪</div>
            <div style="flex:1">
                <p style="color:#fff;font-size:15px;font-weight:600">Workouts</p>
                <p style="color:#555;font-size:12px;margin-top:2px">{{ $totalWorkouts }} workout(s) created</p>
            </div>
            <span style="color:#444;font-size:20px">›</span>
        </div>
    </a>

    {{-- Today's attendance --}}
    @php
        $todayAttendance = \App\Models\Attendance::where('trainer_id', Auth::id())
            ->whereDate('attended_at', today())
            ->with('client')
            ->latest('attended_at')
            ->get();
    @endphp

    @if($todayAttendance->isNotEmpty())
        <div class="bfh-section-title" style="margin-top:8px">Today's check-ins</div>
        @foreach($todayAttendance as $record)
            <div class="bfh-card" style="display:flex;align-items:center;gap:12px;margin-bottom:8px">
                <div style="width:38px;height:38px;background:#2a2a2a;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#FF6B00;font-size:12px;font-weight:700;flex-shrink:0">
                    {{ strtoupper(substr($record->client->name, 0, 2)) }}
                </div>
                <div style="flex:1">
                    <p style="color:#fff;font-size:13px;font-weight:500">{{ $record->client->name }}</p>
                    <p style="color:#555;font-size:11px;margin-top:2px">{{ \Carbon\Carbon::parse($record->attended_at)->format('h:i A') }}</p>
                </div>
                <div style="text-align:right">
                    <span class="bfh-badge active" style="text-transform:capitalize">{{ $record->session_slot }}</span>
                    <p style="color:#555;font-size:10px;margin-top:4px">{{ $record->marked_by === 'client' ? 'Self check-in' : 'Trainer marked' }}</p>
                </div>
            </div>
        @endforeach
    @endif

</x-becky-layout>