<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalClients  = User::where('role', 'client')->count();
        $totalTrainers = User::where('role', 'trainer')->count();

        // Revenue
        $totalRevenue = Payment::where('status', '!=', 'unpaid')->sum('amount_paid');

        $todayRevenue = Payment::where('status', '!=', 'unpaid')
            ->whereDate('paid_at', today())
            ->sum('amount_paid');

        $weeklyRevenue = Payment::where('status', '!=', 'unpaid')
            ->whereBetween('paid_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->sum('amount_paid');

        $monthlyRevenue = Payment::where('status', '!=', 'unpaid')
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('amount_paid');

        $revenueByDay = Payment::where('status', '!=', 'unpaid')
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->select(DB::raw('DATE(paid_at) as date'), DB::raw('SUM(amount_paid) as total'))
            ->groupBy(DB::raw('DATE(paid_at)'))
            ->orderBy('date')
            ->get();

        $paymentSummary = Payment::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        // Attendance
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
            ])->count();

        $monthlyAttendance = Attendance::whereMonth('attended_at', now()->month)->count();

        // Subscriptions
        $subscriptions = Subscription::with(['user', 'membership', 'payment'])
            ->latest()
            ->get();

       $expiringSoon = Subscription::where('status', 'active')
    ->whereBetween('end_date', [today(), today()->addDays(3)])
    ->whereHas('membership', function ($q) {
        $q->where('duration_days', '>', 7);
    })
    ->with(['user', 'membership'])
    ->get();

        $clients = User::where('role', 'client')
            ->with(['subscriptions.membership', 'subscriptions.payment'])
            ->get();

        return view('admin.dashboard', compact(
            'totalClients',
            'totalTrainers',
            'totalRevenue',
            'todayRevenue',
            'weeklyRevenue',
            'monthlyRevenue',
            'revenueByDay',
            'paymentSummary',
            'attendanceBySlot',
            'todayAttendance',
            'weeklyAttendance',
            'monthlyAttendance',
            'subscriptions',
            'expiringSoon',
            'clients',
        ));
    }
}