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

    {{-- All subscriptions paginated --}}
    <div class="bfh-section-title" style="margin-top:8px">All subscriptions</div>
    @if($subscriptions->isEmpty())
        <div class="bfh-card" style="text-align:center;padding:20px">
            <p style="color:#555;font-size:13px">No subscriptions yet.</p>
        </div>
    @else
        @foreach($subscriptions as $sub)
            <a href="{{ route('admin.clients.show', $sub->user->id) }}" style="text-decoration:none">
                <div class="bfh-card" style="margin-bottom:10px">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
                        <div style="display:flex;align-items:center;gap:10px">
                            <div style="width:36px;height:36px;background:#2a2a2a;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#FF6B00;font-size:12px;font-weight:700;flex-shrink:0">
                                {{ strtoupper(substr($sub->user->name, 0, 2)) }}
                            </div>
                            <div>
                                <p style="color:#fff;font-size:14px;font-weight:600">{{ $sub->user->name }}</p>
                                <p style="color:#555;font-size:11px">{{ $sub->user->phone }}</p>
                            </div>
                        </div>
                        <span class="bfh-badge {{ $sub->status }}">{{ $sub->status }}</span>
                    </div>
                    <div class="bfh-divider" style="margin:0 0 12px"></div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
                        <div>
                            <p style="color:#666;font-size:10px;text-transform:uppercase;letter-spacing:1px">Package</p>
                            <p style="color:#aaa;font-size:13px;font-weight:600;margin-top:2px">{{ $sub->membership->name }}</p>
                        </div>
                        <div>
                            <p style="color:#666;font-size:10px;text-transform:uppercase;letter-spacing:1px">Total Due</p>
                            <p style="color:#fff;font-size:13px;font-weight:600;margin-top:2px">UGX {{ number_format($sub->custom_price ?? $sub->membership->price) }}</p>
                        </div>
                        <div>
                            <p style="color:#666;font-size:10px;text-transform:uppercase;letter-spacing:1px">Paid</p>
                            <p style="color:#4caf50;font-size:13px;font-weight:600;margin-top:2px">UGX {{ number_format($sub->payment->amount_paid ?? 0) }}</p>
                        </div>
                        <div>
                            <p style="color:#666;font-size:10px;text-transform:uppercase;letter-spacing:1px">Balance</p>
                            <p style="color:#FF6B00;font-size:13px;font-weight:600;margin-top:2px">UGX {{ number_format($sub->payment->balance ?? $sub->membership->price) }}</p>
                        </div>
                    </div>
                    <div style="margin-top:10px;padding-top:10px;border-top:0.5px solid #222">
                        <p style="color:#555;font-size:11px">
                            Expires {{ \Carbon\Carbon::parse($sub->end_date)->format('d M Y') }} ·
                            Started {{ \Carbon\Carbon::parse($sub->start_date)->format('d M Y') }}
                        </p>
                    </div>
                </div>
            </a>
        @endforeach

        {{-- Pagination --}}
        @if($subscriptions->hasPages())
            <div style="display:flex;justify-content:center;gap:8px;margin-top:16px;flex-wrap:wrap">
                @if($subscriptions->onFirstPage())
                    <span style="padding:8px 14px;background:#1e1e1e;color:#444;border-radius:8px;font-size:12px">← Prev</span>
                @else
                    <a href="{{ $subscriptions->previousPageUrl() }}" style="padding:8px 14px;background:#1e1e1e;color:#888;border-radius:8px;font-size:12px;text-decoration:none">← Prev</a>
                @endif
                <span style="padding:8px 14px;background:#FF6B00;color:#fff;border-radius:8px;font-size:12px">{{ $subscriptions->currentPage() }} / {{ $subscriptions->lastPage() }}</span>
                @if($subscriptions->hasMorePages())
                    <a href="{{ $subscriptions->nextPageUrl() }}" style="padding:8px 14px;background:#1e1e1e;color:#888;border-radius:8px;font-size:12px;text-decoration:none">Next →</a>
                @else
                    <span style="padding:8px 14px;background:#1e1e1e;color:#444;border-radius:8px;font-size:12px">Next →</span>
                @endif
            </div>
        @endif
    @endif

</x-becky-layout>