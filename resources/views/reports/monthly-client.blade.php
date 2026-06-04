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
        .summary-box { display: inline-block; background: #f3f4f6; border-radius: 8px; padding: 12px 20px; margin-right: 12px; margin-bottom: 16px; }
        .summary-box .value { font-size: 22px; font-weight: bold; color: #4f46e5; margin: 0; }
        .summary-box .label { font-size: 11px; color: #888; margin: 0; }
        .footer { margin-top: 40px; font-size: 11px; color: #aaa; text-align: center; }
    </style>
</head>
<body>

    <h1>Monthly Report — {{ $month }}</h1>
    <h2>{{ $user->name }} — Client Report</h2>

    <div class="summary-box">
        <p class="value">{{ $attendanceCount }}</p>
        <p class="label">Sessions Attended</p>
    </div>

    @if($subscription)
        <div class="summary-box">
            <p class="value">{{ $subscription->membership->name }}</p>
            <p class="label">Current Package</p>
        </div>
        <div class="summary-box">
            <p class="value">UGX {{ number_format($subscription->payment->amount_paid ?? 0) }}</p>
            <p class="label">Amount Paid</p>
        </div>
        <div class="summary-box">
            <p class="value">UGX {{ number_format($subscription->payment->balance ?? $subscription->membership->price) }}</p>
            <p class="label">Balance Due</p>
        </div>
    @endif

    <h3>Attendance This Month</h3>
    @if($attendance->isEmpty())
        <p>No attendance recorded this month.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Session</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attendance as $record)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($record->attended_at)->format('d M Y') }}</td>
                        <td style="text-transform:capitalize">{{ $record->session_slot }}</td>
                        <td>{{ \Carbon\Carbon::parse($record->attended_at)->format('h:i A') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <h3>Assigned Workouts</h3>
    @if($workouts->isEmpty())
        <p>No workouts assigned this month.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Workout</th>
                    <th>Description</th>
                    <th>Assigned</th>
                </tr>
            </thead>
            <tbody>
                @foreach($workouts as $assignment)
                    <tr>
                        <td>{{ $assignment->workout->title }}</td>
                        <td>{{ $assignment->workout->description }}</td>
                        <td>{{ \Carbon\Carbon::parse($assignment->assigned_at)->format('d M Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        Generated on {{ now()->format('d M Y, h:i A') }} — Gym Management System
    </div>

</body>
</html>