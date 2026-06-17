<x-becky-layout>

    <div style="margin-bottom:24px">
        <p style="color:#777;font-size:13px">Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }} 💪</p>
        <h2 style="color:#fff;font-size:22px;font-weight:800;margin-top:4px">
            Hey, <span style="color:#FF6B00">{{ explode(' ', Auth::user()->name)[0] }}</span>
        </h2>
    </div>

    @php
        $totalClients = \App\Models\User::where('role', 'client')->count();
        $todayAttendance = \App\Models\Attendance::where('trainer_id', Auth::id())
            ->whereDate('attended_at', today())
            ->count();
        $totalWorkouts = \App\Models\Workout::where('trainer_id', Auth::id())->count();
    @endphp

    <div class="bfh-stat-grid">
        <div class="bfh-stat">
            <div class="bfh-stat-label">Clients</div>
            <div class="bfh-stat-value">{{ $totalClients }}</div>
            <div class="bfh-stat-sub">Registered</div>
        </div>
        <div class="bfh-stat">
            <div class="bfh-stat-label">Today</div>
            <div class="bfh-stat-value">{{ $todayAttendance }}</div>
            <div class="bfh-stat-sub">Attended</div>
        </div>
    </div>

    <div class="bfh-section-title">Quick actions</div>

    <a href="{{ route('trainer.clients') }}" class="bfh-card" style="display:flex;align-items:center;gap:14px;text-decoration:none;margin-bottom:12px">
        <div class="bfh-icon-box">👥</div>
        <div style="flex:1">
            <p style="color:#fff;font-size:15px;font-weight:600">My Clients</p>
            <p style="color:#555;font-size:12px;margin-top:2px">View all registered clients</p>
        </div>
        <span style="color:#444;font-size:20px">›</span>
    </a>

    <a href="{{ route('trainer.attendance') }}" class="bfh-card" style="display:flex;align-items:center;gap:14px;text-decoration:none;margin-bottom:12px">
        <div class="bfh-icon-box">✅</div>
        <div style="flex:1">
            <p style="color:#fff;font-size:15px;font-weight:600">Mark Attendance</p>
            <p style="color:#555;font-size:12px;margin-top:2px">Record today's sessions</p>
        </div>
        <span style="color:#444;font-size:20px">›</span>
    </a>

    <a href="{{ route('trainer.workouts') }}" class="bfh-card" style="display:flex;align-items:center;gap:14px;text-decoration:none;margin-bottom:12px">
        <div class="bfh-icon-box">💪</div>
        <div style="flex:1">
            <p style="color:#fff;font-size:15px;font-weight:600">Workouts</p>
            <p style="color:#555;font-size:12px;margin-top:2px">{{ $totalWorkouts }} workout(s) created</p>
        </div>
        <span style="color:#444;font-size:20px">›</span>
    </a>

</x-becky-layout>