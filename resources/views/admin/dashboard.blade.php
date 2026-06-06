<x-becky-layout>

    <div style="margin-bottom:24px">
        <p style="color:#777;font-size:13px">Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}</p>
        <h2 style="color:#fff;font-size:22px;font-weight:800;margin-top:4px">
            Admin <span style="color:#FF6B00">Dashboard</span>
        </h2>
    </div>

    {{-- Top stats --}}
    <div class="bfh-stat-grid">
        <div class="bfh-stat">
            <div class="bfh-stat-label">Clients</div>
            <div class="bfh-stat-value">{{ $totalClients }}</div>
            <div class="bfh-stat-sub">Registered</div>
        </div>
        <div class="bfh-stat">
            <div class="bfh-stat-label">Trainers</div>
            <div class="bfh-stat-value">{{ $totalTrainers }}</div>
            <div class="bfh-stat-sub">Active</div>
        </div>
        <div class="bfh-stat">
            <div class="bfh-stat-label">This week</div>
            <div class="bfh-stat-value">{{ $weeklyAttendance }}</div>
            <div class="bfh-stat-sub">Attendance</div>
        </div>
        <div class="bfh-stat">
            <div class="bfh-stat-label">This month</div>
            <div class="bfh-stat-value">{{ $monthlyAttendance }}</div>
            <div class="bfh-stat-sub">Attendance</div>
        </div>
    </div>

    {{-- Revenue --}}
    <div class="bfh-section-title">Revenue</div>
    <div class="bfh-stat-grid">
        <div class="bfh-stat">
            <div class="bfh-stat-label">Today</div>
            <div class="bfh-stat-value" style="font-size:16px">{{ number_format($todayRevenue) }}</div>
            <div class="bfh-stat-sub">UGX</div>
        </div>
        <div class="bfh-stat">
            <div class="bfh-stat-label">This week</div>
            <div class="bfh-stat-value" style="font-size:16px">{{ number_format($weeklyRevenue) }}</div>
            <div class="bfh-stat-sub">UGX</div>
        </div>
        <div class="bfh-stat">
            <div class="bfh-stat-label">This month</div>
            <div class="bfh-stat-value" style="font-size:16px">{{ number_format($monthlyRevenue) }}</div>
            <div class="bfh-stat-sub">UGX</div>
        </div>
        <div class="bfh-stat">
            <div class="bfh-stat-label">All time</div>
            <div class="bfh-stat-value" style="font-size:16px">{{ number_format($totalRevenue) }}</div>
            <div class="bfh-stat-sub">UGX</div>
        </div>
    </div>

    {{-- Payment status --}}
    <div class="bfh-section-title">Payment status</div>
    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;margin-bottom:16px">
        <div class="bfh-stat" style="text-align:center">
            <div class="bfh-stat-value" style="color:#4caf50;font-size:28px">{{ $paymentSummary['paid']->total ?? 0 }}</div>
            <div class="bfh-stat-sub">Paid</div>
        </div>
        <div class="bfh-stat" style="text-align:center">
            <div class="bfh-stat-value" style="color:#FF6B00;font-size:28px">{{ $paymentSummary['half-paid']->total ?? 0 }}</div>
            <div class="bfh-stat-sub">Half paid</div>
        </div>
        <div class="bfh-stat" style="text-align:center">
            <div class="bfh-stat-value" style="color:#ff4444;font-size:28px">{{ $paymentSummary['unpaid']->total ?? 0 }}</div>
            <div class="bfh-stat-sub">Unpaid</div>
        </div>
    </div>

    {{-- Attendance by session --}}
    <div class="bfh-section-title">Attendance by session</div>
    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;margin-bottom:16px">
        @foreach(['morning' => ['🌅','5:30–8am'], 'midday' => ['☀️','8am–3:30pm'], 'evening' => ['🌙','3:30–9pm']] as $slot => $info)
            <div class="bfh-stat" style="text-align:center">
                <p style="font-size:20px">{{ $info[0] }}</p>
                <div class="bfh-stat-value" style="font-size:24px">{{ $attendanceBySlot[$slot]->total ?? 0 }}</div>
                <div class="bfh-stat-label" style="text-transform:capitalize;margin-top:4px">{{ $slot }}</div>
                <div class="bfh-stat-sub">{{ $info[1] }}</div>
            </div>
        @endforeach
    </div>

    {{-- Expiring soon --}}
    @if($expiringSoon->isNotEmpty())
        <div class="bfh-section-title" style="color:#FF6B00">⚠️ Expiring within 3 days</div>
        @foreach($expiringSoon as $sub)
            <div class="bfh-card orange-border" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px">
                <div>
                    <p style="color:#fff;font-size:14px;font-weight:600">{{ $sub->user->name }}</p>
                    <p style="color:#555;font-size:12px;margin-top:2px">{{ $sub->membership->name }}</p>
                </div>
                <p style="color:#FF6B00;font-size:12px;font-weight:600">{{ \Carbon\Carbon::parse($sub->end_date)->format('d M Y') }}</p>
            </div>
        @endforeach
    @endif

    {{-- Today's attendance --}}
    <div class="bfh-section-title">Today's attendance — {{ now()->format('d M Y') }}</div>
    @if($todayAttendance->isEmpty())
        <div class="bfh-card" style="text-align:center;padding:20px">
            <p style="color:#555;font-size:13px">No attendance recorded today yet.</p>
        </div>
    @else
        @foreach($todayAttendance as $record)
            <div class="bfh-card" style="display:flex;align-items:center;gap:12px;margin-bottom:10px">
                <div style="width:38px;height:38px;background:#2a2a2a;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#FF6B00;font-size:12px;font-weight:700;flex-shrink:0">
                    {{ strtoupper(substr($record->client->name, 0, 2)) }}
                </div>
                <div style="flex:1">
                    <p style="color:#fff;font-size:13px;font-weight:500">{{ $record->client->name }}</p>
                    <p style="color:#555;font-size:11px">{{ \Carbon\Carbon::parse($record->attended_at)->format('h:i A') }}</p>
                </div>
                <span class="bfh-badge active" style="text-transform:capitalize">{{ $record->session_slot }}</span>
            </div>
        @endforeach
    @endif

    {{-- Daily revenue table --}}
    <div class="bfh-section-title">Daily revenue — {{ now()->format('F Y') }}</div>
    @if($revenueByDay->isEmpty())
        <div class="bfh-card" style="text-align:center;padding:20px">
            <p style="color:#555;font-size:13px">No payments recorded this month yet.</p>
        </div>
    @else
        <div class="bfh-card">
            <table class="bfh-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th style="text-align:right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($revenueByDay as $day)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($day->date)->format('d M Y') }}</td>
                            <td style="text-align:right;color:#FF6B00;font-weight:600">UGX {{ number_format($day->total) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td style="color:#fff;font-weight:700;padding-top:12px">Monthly total</td>
                        <td style="text-align:right;color:#FF6B00;font-weight:700;padding-top:12px">UGX {{ number_format($revenueByDay->sum('total')) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endif

    {{-- All subscriptions --}}
    <div class="bfh-section-title" style="margin-top:8px">All subscriptions</div>
    @if($subscriptions->isEmpty())
        <div class="bfh-card" style="text-align:center;padding:20px">
            <p style="color:#555;font-size:13px">No subscriptions yet.</p>
        </div>
    @else
        @foreach($subscriptions as $sub)
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
                    </div>UGX {{ number_format($sub->custom_price ?? $sub->membership->price) }}
                    <div>
                        <p style="color:#666;font-size:10px;text-transform:uppercase;letter-spacing:1px">Total Due</p>
                        <p style="color:#fff;font-size:13px;font-weight:600;margin-top:2px"></p>
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
        @endforeach
    @endif

    {{-- Clients overview --}}
    <div class="bfh-section-title" style="margin-top:8px">Clients overview</div>
    @if($clients->isEmpty())
        <div class="bfh-card" style="text-align:center;padding:20px">
            <p style="color:#555;font-size:13px">No clients registered yet.</p>
        </div>
    @else
        @foreach($clients as $client)
           @php $sub = $client->subscriptions->sortByDesc('created_at')->first(); @endphp
            <div class="bfh-card" style="display:flex;align-items:center;gap:12px;margin-bottom:10px">
                <div style="width:40px;height:40px;background:#2a2a2a;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#FF6B00;font-size:13px;font-weight:700;flex-shrink:0">
                    {{ strtoupper(substr($client->name, 0, 2)) }}
                </div>
                <div style="flex:1">
                    <p style="color:#fff;font-size:13px;font-weight:500">{{ $client->name }}</p>
                    <p style="color:#555;font-size:11px">{{ $client->phone }}</p>
                </div>
                <div style="text-align:right">
                    @if($sub)
                        <span class="bfh-badge {{ $sub->status }}">{{ $sub->status }}</span>
                        <p style="color:#555;font-size:11px;margin-top:4px">{{ $sub->membership->name }}</p>
                    @else
                        <span class="bfh-badge expired">No package</span>
                    @endif
                </div>
            </div>
        @endforeach
    @endif

</x-becky-layout>