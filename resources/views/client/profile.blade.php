<x-becky-layout>

    {{-- Profile header --}}
    <div style="text-align:center;padding:16px 0 28px">
        <div style="width:80px;height:80px;background:#1e1e1e;border:2px solid #FF6B00;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#FF6B00;font-size:28px;font-weight:800;margin:0 auto 14px">
            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
        </div>
        <h2 style="color:#fff;font-size:20px;font-weight:700">{{ Auth::user()->name }}</h2>
        <p style="color:#666;font-size:13px;margin-top:4px">{{ Auth::user()->email }}</p>
        <div style="margin-top:10px;display:flex;justify-content:center;gap:8px">
            <span class="bfh-badge {{ Auth::user()->role }}">{{ Auth::user()->role }}</span>
            @if($subscription)
                <span class="bfh-badge {{ $subscription->status }}">{{ $subscription->status }}</span>
            @endif
        </div>
    </div>

    {{-- Stats --}}
    <div class="bfh-stat-grid">
        <div class="bfh-stat">
            <div class="bfh-stat-label">Total sessions</div>
            <div class="bfh-stat-value">{{ $totalAttendance }}</div>
            <div class="bfh-stat-sub">All time</div>
        </div>
        <div class="bfh-stat">
            <div class="bfh-stat-label">This month</div>
            <div class="bfh-stat-value">{{ $monthAttendance }}</div>
            <div class="bfh-stat-sub">Sessions attended</div>
        </div>
    </div>

    {{-- Membership --}}
    @if($subscription)
        <div class="bfh-section-title">Current membership</div>
        <div class="bfh-card orange-border" style="margin-bottom:20px">
            <div class="bfh-row">
                <div>
                    <p style="color:#fff;font-size:14px;font-weight:600">{{ $subscription->membership->name }}</p>
                    <p style="color:#FF6B00;font-size:13px;margin-top:4px">UGX {{ number_format($subscription->membership->price) }}</p>
                    <p style="color:#555;font-size:11px;margin-top:2px">Expires {{ \Carbon\Carbon::parse($subscription->end_date)->format('d M Y') }}</p>
                    <p style="color:#555;font-size:11px;margin-top:2px">Member since {{ Auth::user()->created_at->format('M Y') }}</p>
                </div>
                <span class="bfh-badge {{ $subscription->status }}">{{ $subscription->status }}</span>
            </div>
        </div>
    @endif

    {{-- Edit profile --}}
    <div class="bfh-section-title">Edit profile</div>
    <div class="bfh-card" style="margin-bottom:14px">
        <form method="POST" action="{{ route('client.profile.update') }}">
            @csrf
            <div class="bfh-form-group">
                <label class="bfh-form-label">Full name</label>
                <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" class="bfh-input" placeholder="Your full name">
                @error('name')<p class="bfh-error">{{ $message }}</p>@enderror
            </div>
            <div class="bfh-form-group">
                <label class="bfh-form-label">Phone number</label>
                <input type="text" name="phone" value="{{ old('phone', Auth::user()->phone) }}" class="bfh-input" placeholder="e.g. 0771234567">
                @error('phone')<p class="bfh-error">{{ $message }}</p>@enderror
            </div>
            <div class="bfh-form-group" style="margin-bottom:0">
                <label class="bfh-form-label">Email address</label>
                <input type="text" value="{{ Auth::user()->email }}" class="bfh-input" disabled>
            </div>
            <div class="bfh-divider"></div>
            <button type="submit" class="bfh-btn">Save changes</button>
        </form>
    </div>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="bfh-btn danger">Log out</button>
    </form>

</x-becky-layout>