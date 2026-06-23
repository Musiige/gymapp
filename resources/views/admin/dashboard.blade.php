<x-becky-layout>

    <div style="margin-bottom:24px">
        <p style="color:#777;font-size:13px">Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}</p>
        <h2 style="color:#fff;font-size:22px;font-weight:800;margin-top:4px">
            Admin <span style="color:#FF6B00">Dashboard</span>
        </h2>
    </div>

    {{-- Top stats --}}
    <div class="bfh-stat-grid">
        <a href="{{ route('admin.clients') }}" style="text-decoration:none">
            <div class="bfh-stat">
                <div class="bfh-stat-label">Clients</div>
                <div class="bfh-stat-value">{{ $totalClients }}</div>
                <div class="bfh-stat-sub">Registered</div>
            </div>
        </a>
        <a href="{{ route('admin.staff') }}" style="text-decoration:none">
            <div class="bfh-stat">
                <div class="bfh-stat-label">Trainers</div>
                <div class="bfh-stat-value">{{ $totalTrainers }}</div>
                <div class="bfh-stat-sub">Active</div>
            </div>
        </a>
        <a href="{{ route('admin.reports.attendance') }}?filter=week" style="text-decoration:none">
            <div class="bfh-stat">
                <div class="bfh-stat-label">This week</div>
                <div class="bfh-stat-value">{{ $weeklyAttendance }}</div>
                <div class="bfh-stat-sub">Attendance</div>
            </div>
        </a>
        <a href="{{ route('admin.reports.attendance') }}?filter=month" style="text-decoration:none">
            <div class="bfh-stat">
                <div class="bfh-stat-label">This month</div>
                <div class="bfh-stat-value">{{ $monthlyAttendance }}</div>
                <div class="bfh-stat-sub">Attendance</div>
            </div>
        </a>
    </div>

    {{-- Revenue --}}
    <div class="bfh-section-title">Revenue</div>
    <div class="bfh-stat-grid">
        <a href="{{ route('admin.reports.revenue') }}?filter=today" style="text-decoration:none">
            <div class="bfh-stat">
                <div class="bfh-stat-label">Today</div>
                <div class="bfh-stat-value" style="font-size:16px">{{ number_format($todayRevenue) }}</div>
                <div class="bfh-stat-sub">UGX</div>
            </div>
        </a>
        <a href="{{ route('admin.reports.revenue') }}?filter=week" style="text-decoration:none">
            <div class="bfh-stat">
                <div class="bfh-stat-label">This week</div>
                <div class="bfh-stat-value" style="font-size:16px">{{ number_format($weeklyRevenue) }}</div>
                <div class="bfh-stat-sub">UGX</div>
            </div>
        </a>
        <a href="{{ route('admin.reports.revenue') }}?filter=month" style="text-decoration:none">
            <div class="bfh-stat">
                <div class="bfh-stat-label">This month</div>
                <div class="bfh-stat-value" style="font-size:16px">{{ number_format($monthlyRevenue) }}</div>
                <div class="bfh-stat-sub">UGX</div>
            </div>
        </a>
        <a href="{{ route('admin.reports.revenue') }}?filter=all" style="text-decoration:none">
            <div class="bfh-stat">
                <div class="bfh-stat-label">All time</div>
                <div class="bfh-stat-value" style="font-size:16px">{{ number_format($totalRevenue) }}</div>
                <div class="bfh-stat-sub">UGX</div>
            </div>
        </a>
    </div>

    {{-- Payment status --}}
    <div class="bfh-section-title">Payment status</div>
    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;margin-bottom:16px">
        <a href="{{ route('admin.reports.payment-status') }}?status=paid" style="text-decoration:none">
            <div class="bfh-stat" style="text-align:center">
                <div class="bfh-stat-value" style="color:#4caf50;font-size:28px">{{ $paymentSummary['paid']->total ?? 0 }}</div>
                <div class="bfh-stat-sub">Paid</div>
            </div>
        </a>
        <a href="{{ route('admin.reports.payment-status') }}?status=half-paid" style="text-decoration:none">
            <div class="bfh-stat" style="text-align:center">
                <div class="bfh-stat-value" style="color:#FF6B00;font-size:28px">{{ $paymentSummary['half-paid']->total ?? 0 }}</div>
                <div class="bfh-stat-sub">Half paid</div>
            </div>
        </a>
        <a href="{{ route('admin.reports.payment-status') }}?status=unpaid" style="text-decoration:none">
            <div class="bfh-stat" style="text-align:center">
                <div class="bfh-stat-value" style="color:#ff4444;font-size:28px">{{ $paymentSummary['unpaid']->total ?? 0 }}</div>
                <div class="bfh-stat-sub">Unpaid</div>
            </div>
        </a>
    </div>

    {{-- Attendance by session --}}
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px">
        <div class="bfh-section-title" style="margin-bottom:0">Attendance by session</div>
        <div style="display:flex;gap:6px">
            @foreach(['today' => 'Today', 'week' => 'Week', 'month' => 'Month'] as $val => $label)
                <a href="?attendance_filter={{ $val }}"
                   style="padding:4px 10px;border-radius:20px;font-size:11px;font-weight:700;text-decoration:none;
                   {{ $attendanceFilter === $val ? 'background:#FF6B00;color:#fff' : 'background:#1e1e1e;color:#666;border:0.5px solid #333' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;margin-bottom:16px">
        @foreach(['morning' => ['🌅','5:30–8am'], 'midday' => ['☀️','8am–3:30pm'], 'evening' => ['🌙','3:30–9pm']] as $slot => $info)
            <a href="{{ route('admin.attendance.session') }}?slot={{ $slot }}&filter={{ $attendanceFilter }}" style="text-decoration:none">
                <div class="bfh-stat" style="text-align:center">
                    <p style="font-size:20px">{{ $info[0] }}</p>
                    <div class="bfh-stat-value" style="font-size:24px">{{ isset($attendanceBySlot[$slot]) ? $attendanceBySlot[$slot]->count() : 0 }}</div>
                    <div class="bfh-stat-label" style="text-transform:capitalize;margin-top:4px">{{ $slot }}</div>
                    <div class="bfh-stat-sub">{{ $info[1] }}</div>
                </div>
            </a>
        @endforeach
    </div>

    {{-- Expiring soon --}}
    @if($expiringSoon->isNotEmpty())
        <div class="bfh-section-title" style="color:#FF6B00">⚠️ Expiring within 3 days</div>
        @foreach($expiringSoon as $sub)
            <a href="{{ route('admin.clients.show', $sub->user->id) }}" style="text-decoration:none">
                <div class="bfh-card orange-border" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px">
                    <div>
                        <p style="color:#fff;font-size:14px;font-weight:600">{{ $sub->user->name }}</p>
                        <p style="color:#555;font-size:12px;margin-top:2px">{{ $sub->membership->name }}</p>
                    </div>
                    <p style="color:#FF6B00;font-size:12px;font-weight:600">{{ \Carbon\Carbon::parse($sub->end_date)->format('d M Y') }}</p>
                </div>
            </a>
        @endforeach
    @endif

   {{-- Trainer allowances link --}}
    <a href="{{ route('admin.allowances') }}" class="bfh-card" style="display:flex;align-items:center;gap:14px;text-decoration:none;margin-top:8px;margin-bottom:16px">
        <div class="bfh-icon-box">💰</div>
        <div style="flex:1">
            <p style="color:#fff;font-size:15px;font-weight:600">Trainer Allowances</p>
            <p style="color:#555;font-size:12px;margin-top:2px">View balances owed and credit</p>
        </div>
        <span style="color:#444;font-size:20px">›</span>
    </a>

    {{-- All subscriptions link --}}
    <a href="{{ route('admin.reports.subscriptions') }}" class="bfh-card" style="display:flex;align-items:center;gap:14px;text-decoration:none;margin-top:8px">
        <div class="bfh-icon-box">📋</div>
        <div style="flex:1">
            <p style="color:#fff;font-size:15px;font-weight:600">All Subscriptions</p>
            <p style="color:#555;font-size:12px;margin-top:2px">{{ $subscriptions->total() }} total subscription(s)</p>
        </div>
        <span style="color:#444;font-size:20px">›</span>
    </a>

</x-becky-layout>