<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; font-size: 13px; color: #333; }
        h1 { font-size: 20px; color: #4f46e5; margin-bottom: 4px; }
        h2 { font-size: 15px; color: #555; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th { background: #4f46e5; color: white; padding: 8px 10px; text-align: left; font-size: 12px; }
        td { padding: 8px 10px; border-bottom: 1px solid #eee; }
        tr:nth-child(even) { background: #f9f9f9; }
        .summary { margin-bottom: 24px; }
        .summary-box { display: inline-block; background: #f3f4f6; border-radius: 8px; padding: 12px 20px; margin-right: 12px; }
        .summary-box p { margin: 0; }
        .summary-box .value { font-size: 22px; font-weight: bold; color: #4f46e5; }
        .summary-box .label { font-size: 11px; color: #888; }
        .badge-paid { color: #16a34a; font-weight: bold; }
        .badge-half { color: #d97706; font-weight: bold; }
        .badge-unpaid { color: #dc2626; font-weight: bold; }
        .footer { margin-top: 40px; font-size: 11px; color: #aaa; text-align: center; }
    </style>
</head>
<body>

    <h1>Monthly Report — {{ $month }}</h1>
    <h2>Admin Summary Report</h2>

    <div class="summary">
        <div class="summary-box">
            <p class="value">UGX {{ number_format($totalRevenue) }}</p>
            <p class="label">Total Revenue</p>
        </div>
        <div class="summary-box">
            <p class="value">{{ $totalClients }}</p>
            <p class="label">Total Clients</p>
        </div>
        <div class="summary-box">
            <p class="value">{{ $totalAttendance }}</p>
            <p class="label">Total Attendance</p>
        </div>
    </div>

    <h3>Payment Status</h3>
    <div class="summary">
        <div class="summary-box">
            <p class="value" style="color:#16a34a">{{ $paidCount }}</p>
            <p class="label">Fully Paid</p>
        </div>
        <div class="summary-box">
            <p class="value" style="color:#d97706">{{ $halfPaidCount }}</p>
            <p class="label">Half Paid</p>
        </div>
        <div class="summary-box">
            <p class="value" style="color:#dc2626">{{ $unpaidCount }}</p>
            <p class="label">Unpaid</p>
        </div>
    </div>

    <h3>Attendance by Session</h3>
    <table>
        <thead>
            <tr>
                <th>Session</th>
                <th>Time</th>
                <th>Total Attendance</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Morning</td>
                <td>5:30am – 8:00am</td>
                <td>{{ $attendanceBySlot['morning'] ?? 0 }}</td>
            </tr>
            <tr>
                <td>Midday</td>
                <td>8:00am – 3:30pm</td>
                <td>{{ $attendanceBySlot['midday'] ?? 0 }}</td>
            </tr>
            <tr>
                <td>Evening</td>
                <td>3:30pm – 9:00pm</td>
                <td>{{ $attendanceBySlot['evening'] ?? 0 }}</td>
            </tr>
        </tbody>
    </table>

    <h3>All Subscriptions This Month</h3>
    <table>
        <thead>
            <tr>
                <th>Client</th>
                <th>Package</th>
                <th>Amount Paid</th>
                <th>Balance</th>
                <th>Status</th>
                <th>Expires</th>
            </tr>
        </thead>
        <tbody>
            @foreach($subscriptions as $sub)
                <tr>
                    <td>{{ $sub->user->name }}</td>
                    <td>{{ $sub->membership->name }}</td>
                    <td>UGX {{ number_format($sub->payment->amount_paid ?? 0) }}</td>
                    <td>UGX {{ number_format($sub->payment->balance ?? $sub->membership->price) }}</td>
                    <td>
                        @if($sub->payment)
                            <span class="badge-{{ $sub->payment->status === 'paid' ? 'paid' : ($sub->payment->status === 'half-paid' ? 'half' : 'unpaid') }}">
                                {{ ucfirst($sub->payment->status) }}
                            </span>
                        @else
                            <span class="badge-unpaid">Unpaid</span>
                        @endif
                    </td>
                    <td>{{ \Carbon\Carbon::parse($sub->end_date)->format('d M Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Generated on {{ now()->format('d M Y, h:i A') }} — Gym Management System
    </div>

</body>
</html>