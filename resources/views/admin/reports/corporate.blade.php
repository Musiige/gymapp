<x-becky-layout>

    <div style="margin-bottom:24px">
        <a href="{{ route('admin.clients') }}" style="color:#555;font-size:13px;text-decoration:none">← Back to clients</a>
        <h2 style="color:#fff;font-size:22px;font-weight:800;margin-top:8px">
            Corporate <span style="color:#FF6B00">Report</span>
        </h2>
    </div>

   {{-- Single date filter --}}
    <form method="GET" action="{{ route('admin.reports.corporate') }}" style="margin-bottom:16px">
        <label class="bfh-form-label" style="display:block;margin-bottom:6px">Pick a date</label>
        <div style="position:relative">
            <input type="date" name="date" value="{{ $date }}" class="bfh-input" style="padding-right:40px" onchange="this.form.submit()">
            <span style="position:absolute;right:14px;top:50%;transform:translateY(-50%);font-size:16px;pointer-events:none">📅</span>
        </div>
    </form>

    @if($date)
        <p style="color:#777;font-size:12px;margin-bottom:16px">
            Showing {{ \Carbon\Carbon::parse($date)->format('d M Y') }} and its month totals ·
            <a href="{{ route('admin.reports.corporate') }}" style="color:#FF6B00;text-decoration:none">Reset to current month</a>
        </p>
    @endif

    {{-- Grand total --}}
    <div class="bfh-card orange-border" style="margin-bottom:20px;text-align:center">
        <div class="bfh-stat-label">Grand total — {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}</div>
        <div class="bfh-stat-value" style="font-size:36px">{{ $grandTotal }}</div>
        <div class="bfh-stat-sub">Total sessions across all companies</div>
    </div>

    @if(empty($report))
        <div class="bfh-card" style="text-align:center;padding:20px">
            <p style="color:#555;font-size:13px">No corporate clients added yet.</p>
        </div>
    @else
        @foreach($report as $company => $data)
            <div class="bfh-card" style="margin-bottom:16px">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
                    <p style="color:#fff;font-size:15px;font-weight:700">{{ $company }}</p>
                    <span class="bfh-badge active">{{ $data['member_count'] }} member(s)</span>
                </div>

            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px;margin-bottom:14px">
                    <a href="{{ route('admin.reports.corporate.attendance') }}?company={{ urlencode($company) }}&period=today" style="text-decoration:none">
                        <div style="text-align:center">
                            <p style="color:#666;font-size:10px;text-transform:uppercase;letter-spacing:1px">Today</p>
                            <p style="color:#FF6B00;font-size:18px;font-weight:700;margin-top:4px">{{ $data['today'] }}</p>
                        </div>
                    </a>
                    <a href="{{ route('admin.reports.corporate.attendance') }}?company={{ urlencode($company) }}&period=week" style="text-decoration:none">
                        <div style="text-align:center">
                            <p style="color:#666;font-size:10px;text-transform:uppercase;letter-spacing:1px">This week</p>
                            <p style="color:#FF6B00;font-size:18px;font-weight:700;margin-top:4px">{{ $data['week'] }}</p>
                        </div>
                    </a>
                   <a href="{{ route('admin.reports.corporate.attendance') }}?company={{ urlencode($company) }}&period=month&month={{ $month }}{{ $date ? '&date='.$date : '' }}" style="text-decoration:none">
                        <div style="text-align:center">
                            <p style="color:#666;font-size:10px;text-transform:uppercase;letter-spacing:1px">{{ $date ? 'Selected day' : 'This month' }}</p>
                            <p style="color:#FF6B00;font-size:18px;font-weight:700;margin-top:4px">{{ $data['month_total'] }}</p>
                        </div>
                    </a>
                </div>

                <div class="bfh-divider"></div>

                <p style="color:#666;font-size:10px;text-transform:uppercase;letter-spacing:1px;margin:10px 0">Sessions this month</p>
         <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px">
                    <a href="{{ route('admin.reports.corporate.attendance') }}?company={{ urlencode($company) }}&period=month&month={{ $month }}&slot=morning" style="text-decoration:none">
                        <div style="text-align:center;background:#0a0a0a;border-radius:8px;padding:10px">
                            <p style="font-size:16px">🌅</p>
                            <p style="color:#aaa;font-size:14px;font-weight:600;margin-top:4px">{{ $data['morning'] }}</p>
                            <p style="color:#555;font-size:10px">Morning</p>
                        </div>
                    </a>
                    <a href="{{ route('admin.reports.corporate.attendance') }}?company={{ urlencode($company) }}&period=month&month={{ $month }}&slot=midday" style="text-decoration:none">
                        <div style="text-align:center;background:#0a0a0a;border-radius:8px;padding:10px">
                            <p style="font-size:16px">☀️</p>
                            <p style="color:#aaa;font-size:14px;font-weight:600;margin-top:4px">{{ $data['midday'] }}</p>
                            <p style="color:#555;font-size:10px">Midday</p>
                        </div>
                    </a>
                    <a href="{{ route('admin.reports.corporate.attendance') }}?company={{ urlencode($company) }}&period=month&month={{ $month }}&slot=evening" style="text-decoration:none">
                        <div style="text-align:center;background:#0a0a0a;border-radius:8px;padding:10px">
                            <p style="font-size:16px">🌙</p>
                            <p style="color:#aaa;font-size:14px;font-weight:600;margin-top:4px">{{ $data['evening'] }}</p>
                            <p style="color:#555;font-size:10px">Evening</p>
                        </div>
                    </a>
                </div>
            </div>
        @endforeach
    @endif

</x-becky-layout>