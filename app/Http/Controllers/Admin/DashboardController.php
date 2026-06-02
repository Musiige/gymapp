<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Membership;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalClients  = User::where('role', 'client')->count();
        $totalTrainers = User::where('role', 'trainer')->count();

        $subscriptions = Subscription::with(['user', 'membership'])
            ->latest()
            ->get();

        $attendanceBySlot = Attendance::select('session_slot', DB::raw('count(*) as total'))
            ->groupBy('session_slot')
            ->get()
            ->keyBy('session_slot');

        $todayAttendance = Attendance::whereDate('attended_at', today())
            ->with('client')
            ->get();

        $weeklyAttendance = Attendance::whereBetween('attended_at', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ])
            ->count();

        $monthlyAttendance = Attendance::whereMonth('attended_at', now()->month)
            ->count();

        $clients = User::where('role', 'client')
            ->with(['subscriptions.membership'])
            ->get();

        return view('admin.dashboard', compact(
            'totalClients',
            'totalTrainers',
            'subscriptions',
            'attendanceBySlot',
            'todayAttendance',
            'weeklyAttendance',
            'monthlyAttendance',
            'clients',
        ));
    }
}