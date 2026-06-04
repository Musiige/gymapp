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
        .footer { margin-top: 40px; font-size: 11px; color: #aaa; text-align: center; }
    </style>
</head>
<body>

    <h1>Monthly Report — {{ $month }}</h1>
    <h2>{{ $trainer->name }} — Trainer Report</h2>

    <h3>Attendance Marked This Month</h3>
    @if($attendance->isEmpty())
        <p>No attendance recorded this month.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Client</th>
                    <th>Session</th>
                    <th>Date</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attendance as $record)
                    <tr>
                        <td>{{ $record->client->name }}</td>
                        <td style="text-transform:capitalize">{{ $record->session_slot }}</td>
                        <td>{{ \Carbon\Carbon::parse($record->attended_at)->format('d M Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($record->attended_at)->format('h:i A') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <h3>Workouts Created This Month</h3>
    @if($workouts->isEmpty())
        <p>No workouts created this month.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
                @foreach($workouts as $workout)
                    <tr>
                        <td>{{ $workout->title }}</td>
                        <td>{{ $workout->description }}</td>
                        <td>{{ \Carbon\Carbon::parse($workout->created_at)->format('d M Y') }}</td>
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