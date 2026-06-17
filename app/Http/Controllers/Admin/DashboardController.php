<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalClients  = User::where('role', 'client')->count();
        $totalTrainers = User::where('role', 'trainer')->count();

        // Revenue
        $totalRevenue = Payment::where('status', '!=', 'unpaid')->sum('amount_paid');
        $todayRevenue = Payment::where('status', '!=', 'unpaid')->whereDate('paid_at', today())->sum('amount_paid');
        $weeklyRevenue = Payment::where('status', '!=', 'unpaid')->whereBetween('paid_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('amount_paid');
        $monthlyRevenue = Payment::where('status', '!=', 'unpaid')->whereMonth('paid_at', now()->month)->whereYear('paid_at', now()->year)->sum('amount_paid');

        // Payment status
        $paymentSummary = Payment::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')->get()->keyBy('status');

        // Attendance by session — filterable
        $attendanceFilter = $request->get('attendance_filter', 'today');
        $attendanceQuery = Attendance::with('client');

        if ($attendanceFilter === 'today') {
            $attendanceQuery->whereDate('attended_at', today());
        } elseif ($attendanceFilter === 'week') {
            $attendanceQuery->whereBetween('attended_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($attendanceFilter === 'month') {
            $attendanceQuery->whereMonth('attended_at', now()->month)->whereYear('attended_at', now()->year);
        }

        $attendanceBySlot = $attendanceQuery->get()->groupBy('session_slot');

        $weeklyAttendance = Attendance::whereBetween('attended_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $monthlyAttendance = Attendance::whereMonth('attended_at', now()->month)->count();

        // Subscriptions paginated
        $subscriptions = Subscription::with(['user', 'membership', 'payment'])
            ->whereIn('id', function ($query) {
                $query->selectRaw('MAX(id)')->from('subscriptions')->groupBy('user_id');
            })
            ->latest()
            ->paginate(10);

        $expiringSoon = Subscription::where('status', 'active')
            ->whereBetween('end_date', [today(), today()->addDays(3)])
            ->whereHas('membership', function ($q) { $q->where('duration_days', '>', 7); })
            ->with(['user', 'membership'])
            ->get();

        return view('admin.dashboard', compact(
            'totalClients', 'totalTrainers',
            'totalRevenue', 'todayRevenue', 'weeklyRevenue', 'monthlyRevenue',
            'paymentSummary', 'attendanceBySlot', 'attendanceFilter',
            'weeklyAttendance', 'monthlyAttendance',
            'subscriptions', 'expiringSoon',
        ));
    }
    public function sessionAttendance(Request $request)
{
    $slot = $request->get('slot', 'morning');
    $filter = $request->get('filter', 'today');

    $query = Attendance::with('client')->where('session_slot', $slot);

    if ($filter === 'today') {
        $query->whereDate('attended_at', today());
    } elseif ($filter === 'week') {
        $query->whereBetween('attended_at', [now()->startOfWeek(), now()->endOfWeek()]);
    } elseif ($filter === 'month') {
        $query->whereMonth('attended_at', now()->month)->whereYear('attended_at', now()->year);
    }

    $records = $query->orderByDesc('attended_at')->get();

    return view('admin.session-attendance', compact('records', 'slot', 'filter'));
}
}