<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use App\Models\Workout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalClients = User::where('role', 'client')->count();

        $todayAttendance = Attendance::where('trainer_id', Auth::id())
            ->whereDate('attended_at', today())
            ->count();

        $totalWorkouts = Workout::where('trainer_id', Auth::id())->count();

        $attendanceBySlot = Attendance::whereDate('attended_at', today())
            ->get()
            ->groupBy('session_slot');

        return view('trainer.dashboard', compact(
            'totalClients', 'todayAttendance', 'totalWorkouts', 'attendanceBySlot'
        ));
    }
    public function sessionAttendance(Request $request)
    {
        $slot = $request->get('slot', 'morning');

        $records = Attendance::with('client')
            ->where('session_slot', $slot)
            ->whereDate('attended_at', today())
            ->orderByDesc('attended_at')
            ->get();

        return view('trainer.session-attendance', compact('records', 'slot'));
    }
    public function attendanceHistory(Request $request)
    {
        $filter = $request->get('filter', 'today');

        $query = Attendance::with('client');

        if ($filter === 'today') {
            $query->whereDate('attended_at', today());
        } elseif ($filter === 'week') {
            $query->whereBetween('attended_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($filter === 'month') {
            $query->whereMonth('attended_at', now()->month)->whereYear('attended_at', now()->year);
        }

        $records = $query->orderByDesc('attended_at')->get();
        $grouped = $records->groupBy(function ($r) {
            return \Carbon\Carbon::parse($r->attended_at)->format('d M Y');
        });

        return view('trainer.attendance-history', compact('grouped', 'filter'));
    }
}