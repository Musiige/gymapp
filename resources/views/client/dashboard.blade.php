<x-becky-layout>
    @php
        $subscription = \App\Models\Subscription::where('user_id', Auth::id())
            ->whereIn('status', ['active', 'pending'])
            ->with(['membership', 'payment'])
            ->latest()
            ->first();

        $workouts = \App\Models\WorkoutAssignment::where('client_id', Auth::id())
            ->with('workout.trainer')
            ->latest()
            ->get();

        $attendanceCount = \App\Models\Attendance::where('user_id', Auth::id())
            ->whereMonth('attended_at', now()->month)
            ->count();
    @endphp

    <div style="padding-top:8px">
        <p style="color:#777;font-size:13px">Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }} 💪</p>
        <h2 style="color:#fff;font-size:22px;font-weight:800;margin-top:4px">
            Welcome, <span style="color:#FF6B00">{{ explode(' ', Auth::user()->name)[0] }}</span>
        </h2>
    </div>

    <div style="height:20px"></div>

    <div class="bfh-stat-grid">
        <div class="bfh-stat">
            <div class="bfh-stat-label">Sessions</div>
            <div class="bfh-stat-value">{{ $attendanceCount }}</div>
            <div class="bfh-stat-sub">This month</div>
        </div>
        <div class="bfh-stat">
            <div class="bfh-stat-label">Balance</div>
            <div class="bfh-stat-value grey">
                UGX {{ number_format($subscription?->payment?->balance ?? ($subscription?->membership?->price ?? 0)) }}
            </div>
            <div class="bfh-stat-sub">
                {{ $subscription?->payment?->status === 'paid' ? 'Fully paid' : 'Outstanding' }}
            </div>
        </div>
    </div>

    <div class="bfh-section-title">My membership</div>

    @if($subscription)
        @php
            $total = $subscription->membership->duration_days;
            $elapsed = now()->diffInDays($subscription->start_date);
            $remaining = max(0, $total - $elapsed);
            $percent = min(100, round(($remaining / $total) * 100));
        @endphp
        <div class="bfh-card orange-border">
            <div class="bfh-row">
                <div>
                    <p style="color:#fff;font-size:15px;font-weight:600">{{ $subscription->membership->name }}</p>
                    <p style="color:#FF6B00;font-size:13px;margin-top:4px">UGX {{ number_format($subscription->membership->price) }}</p>
                    <p style="color:#555;font-size:11px;margin-top:2px">Expires {{ \Carbon\Carbon::parse($subscription->end_date)->format('d M Y') }}</p>
                </div>
                <span class="bfh-badge {{ $subscription->status }}">{{ $subscription->status }}</span>
            </div>
            <div class="bfh-progress-bar">
                <div class="bfh-progress-fill" style="width:{{ $percent }}%"></div>
            </div>
            <div style="display:flex;justify-content:space-between;margin-top:6px">
                <span style="color:#555;font-size:10px">{{ $elapsed }} days elapsed</span>
                <span style="color:#FF6B00;font-size:10px">{{ $percent }}% remaining</span>
            </div>
        </div>

        @if(!$subscription->payment || $subscription->payment->status !== 'paid')
            <a href="{{ route('client.payment', $subscription->id) }}" class="bfh-btn" style="margin-bottom:14px">
                Complete payment
            </a>
        @endif
    @else
        <div class="bfh-card">
            <p style="color:#888;font-size:14px;margin-bottom:14px">No active package. Choose one to get started.</p>
            <a href="{{ route('client.subscription') }}" class="bfh-btn">Choose a package</a>
        </div>
    @endif

    <div class="bfh-section-title">My workouts</div>

    @if($workouts->isEmpty())
        <div class="bfh-card">
            <p style="color:#555;font-size:13px">No workouts assigned yet. Check back after your trainer sets one up.</p>
        </div>
    @else
        @foreach($workouts as $assignment)
            <div class="bfh-card" style="display:flex;align-items:center;gap:12px">
                <div style="width:40px;height:40px;background:#2a2a2a;border:0.5px solid #3a3a3a;border-radius:10px;display:flex;align-items:center;justify-content:center;color:#FF6B00;font-size:16px;font-weight:700;flex-shrink:0">
                    {{ strtoupper(substr($assignment->workout->title, 0, 1)) }}
                </div>
                <div style="flex:1">
                    <p style="color:#fff;font-size:14px;font-weight:500">{{ $assignment->workout->title }}</p>
                    <p style="color:#555;font-size:12px;margin-top:2px">{{ $assignment->workout->trainer->name }}</p>
                    <p style="color:#444;font-size:11px;margin-top:2px">{{ $assignment->workout->description }}</p>
                </div>
            </div>
        @endforeach
    @endif

</x-becky-layout>