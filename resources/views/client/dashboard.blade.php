<x-becky-layout>
  @php
    $subscription = \App\Models\Subscription::where('user_id', Auth::id())
        ->where(function ($q) {
            $q->where('status', 'active')
              ->orWhere(function ($q) {
                  $q->where('status', 'pending')
                    ->where('end_date', '>=', now());
              });
        })
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

    $currentHour = now()->hour;
    $currentSession = $currentHour >= 5 && $currentHour < 8 ? 'morning'
        : ($currentHour >= 8 && $currentHour < 16 ? 'midday'
        : ($currentHour >= 16 && $currentHour < 22 ? 'evening' : 'midday'));

    $alreadyCheckedIn = $currentSession ? \App\Models\Attendance::where('user_id', Auth::id())
        ->where('session_slot', $currentSession)
        ->whereDate('attended_at', today())
        ->exists() : false;

    $trainerMarked = $currentSession ? \App\Models\Attendance::where('user_id', Auth::id())
        ->where('session_slot', $currentSession)
        ->whereDate('attended_at', today())
        ->where('marked_by', 'trainer')
        ->exists() : false;
@endphp

    <div style="margin-bottom:24px">
        <p style="color:#777;font-size:13px">Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }} 💪</p>
        <h2 style="color:#fff;font-size:22px;font-weight:800;margin-top:4px">
            Welcome, <span style="color:#FF6B00">{{ explode(' ', Auth::user()->name)[0] }}</span>
        </h2>
    </div>

    <div class="bfh-stat-grid">
        <a href="{{ route('client.attendance') }}" style="text-decoration:none;color:inherit;display:block">
        <div class="bfh-stat">
            <div class="bfh-stat-label">Sessions</div>
            <div class="bfh-stat-value">{{ $attendanceCount }}</div>
            <div class="bfh-stat-sub">This month</div>
        </div>
        </a>
  @if(!Auth::user()->is_corporate)
        <a href="{{ route('client.payments') }}" style="text-decoration:none;color:inherit;display:block">
        <div class="bfh-stat">
            <div class="bfh-stat-label">Balance</div>
            <div class="bfh-stat-value grey">
                UGX {{ number_format($subscription?->payment?->outstanding_balance ?? ($subscription?->membership?->price ?? 0)) }}
            </div>
            <div class="bfh-stat-sub">
                {{ $subscription?->payment?->status === 'paid' ? 'Fully paid' : 'Outstanding' }}
            </div>
        </div>
        </a>
        @else
        <div class="bfh-stat">
            <div class="bfh-stat-label">Status</div>
            <div class="bfh-stat-value grey" style="font-size:16px">Corporate</div>
            <div class="bfh-stat-sub">{{ Auth::user()->company_name }}</div>
        </div>
        @endif
    </div>

    <div class="bfh-section-title">My membership</div>

    @if($subscription)
        @php
            $total   = max(1, $subscription->membership->duration_days);
            $elapsed = (int) \Carbon\Carbon::parse($subscription->start_date)->diffInDays(now());
            $remaining = max(0, $total - $elapsed);
            $percent = min(100, round(($remaining / $total) * 100));
        @endphp
        <div class="bfh-card orange-border">
            <div class="bfh-row">
              <div>
                    <p style="color:#fff;font-size:15px;font-weight:600">
                        {{ $subscription->membership->name }}
                        @if(Auth::user()->is_corporate)
                            <span style="color:#FF6B00">({{ Auth::user()->company_name }})</span>
                        @endif
                    </p>
                    @if(!Auth::user()->is_corporate)
                        <p style="color:#FF6B00;font-size:13px;margin-top:4px">UGX {{ number_format($subscription->membership->price) }}</p>
                    @endif
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

       @if(!Auth::user()->is_corporate && (!$subscription->payment || $subscription->payment->status !== 'paid'))
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

    {{-- Check in --}}
@php
    $currentHour = now()->hour;
$currentSession = $currentHour >= 5 && $currentHour < 8 ? 'morning'
    : ($currentHour >= 8 && $currentHour < 16 ? 'midday'
    : ($currentHour >= 16 && $currentHour < 22 ? 'evening' : 'midday'));

    $alreadyCheckedIn = $currentSession ? \App\Models\Attendance::where('user_id', Auth::id())
        ->where('session_slot', $currentSession)
        ->whereDate('attended_at', today())
        ->exists() : false;

        $trainerMarked = $currentSession ? \App\Models\Attendance::where('user_id', Auth::id())
    ->where('session_slot', $currentSession)
    ->whereDate('attended_at', today())
    ->where('marked_by', 'trainer')
    ->exists() : false;
@endphp

<div class="bfh-section-title" style="margin-top:8px">Check in</div>

@if($subscription && $subscription->status === 'active' && $subscription->access_granted)
    @if($currentSession)
       @if($alreadyCheckedIn || $trainerMarked)
    <div class="bfh-card" style="text-align:center;padding:20px">
        <p style="font-size:28px;margin-bottom:8px">✅</p>
        @if($trainerMarked)
            <p style="color:#FF6B00;font-size:15px;font-weight:600">Trainer checked you in</p>
        @else
            <p style="color:#4caf50;font-size:15px;font-weight:600">Already checked in</p>
        @endif
        <p style="color:#555;font-size:12px;margin-top:4px;text-transform:capitalize">{{ $currentSession }} session</p>
    </div>
@else
            <form method="POST" action="{{ route('client.checkin') }}">
                @csrf
                <input type="hidden" name="session_slot" value="{{ $currentSession }}">
                <button type="submit" class="bfh-btn" style="margin-bottom:14px;font-size:16px;padding:18px">
                    ✊ Check in — {{ ucfirst($currentSession) }} session
                </button>
            </form>
        @endif
    @else
        <div class="bfh-card" style="text-align:center;padding:20px">
            <p style="color:#555;font-size:13px">No active session right now.</p>
            <p style="color:#444;font-size:12px;margin-top:4px">Sessions: 5:30–8am · 8am–3:30pm · 3:30–9pm</p>
        </div>
    @endif
@elseif($subscription && $subscription->status === 'active' && !$subscription->access_granted)
    <div class="bfh-card" style="text-align:center;padding:20px">
        <p style="color:#FF6B00;font-size:14px;font-weight:600">⏳ Access pending</p>
        <p style="color:#555;font-size:13px;margin-top:6px">Your payment is being processed. Please visit the front desk to complete payment and activate your access.</p>
    </div>
@else
    <div class="bfh-card" style="text-align:center;padding:20px">
        <p style="color:#555;font-size:13px">You need an active membership to check in.</p>
        <a href="{{ route('client.subscription') }}" style="color:#FF6B00;font-size:13px;margin-top:8px;display:block">Choose a package →</a>
    </div>
@endif

  <div class="bfh-section-title" style="margin-top:8px">My workouts</div>

    @if(!$subscription || !$subscription->access_granted)
        <div class="bfh-card">
            <p style="color:#555;font-size:13px">Workouts will be visible once your gym access is activated.</p>
        </div>
    @elseif($workouts->isEmpty())
        <div class="bfh-card">
            <p style="color:#555;font-size:13px">No workouts assigned yet. Check back after your trainer sets one up.</p>
        </div>
    @else
       @foreach($workouts as $assignment)
    <a href="{{ route('client.workout.show', $assignment->id) }}" style="text-decoration:none;display:block">
        <div class="bfh-card" style="display:flex;align-items:center;gap:14px">
            <div class="bfh-icon-box">{{ strtoupper(substr($assignment->workout->title, 0,1)) }}</div>
            <div style="flex:1;min-width:0">
                <p style="color:#fff;font-size:14px;font-weight:600">{{ $assignment->workout->title }}</p>
                <p style="color:#555;font-size:12px;margin-top:2px">by {{ $assignment->workout->trainer->name }}</p>
               <p style="color:#444;font-size:12px;margin-top:4px;line-height:1.6;white-space:pre-line">
                {{ \Illuminate\Support\Str::limit($assignment->workout->description, 80) }}
                </p>
            </div>
            <span style="color:#444;font-size:20px;flex-shrink:0">›</span>
        </div>
    </a>
@endforeach
    @endif

</x-becky-layout>