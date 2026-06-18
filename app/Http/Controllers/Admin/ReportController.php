<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function revenue(Request $request)
    {
        $filter = $request->get('filter', 'today');

        $query = Payment::with(['subscription.user', 'subscription.membership'])
            ->where('status', '!=', 'unpaid');

        if ($filter === 'today') {
            $query->whereDate('paid_at', today());
        } elseif ($filter === 'week') {
            $query->whereBetween('paid_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($filter === 'month') {
            $query->whereMonth('paid_at', now()->month)->whereYear('paid_at', now()->year);
        }
        // all time — no filter

        $payments = $query->orderByDesc('paid_at')->get();
        $total = $payments->sum('amount_paid');

        return view('admin.reports.revenue', compact('payments', 'total', 'filter'));
    }

    public function attendance(Request $request)
    {
        $filter = $request->get('filter', 'week');

        $query = Attendance::with('client');

        if ($filter === 'week') {
            $query->whereBetween('attended_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($filter === 'month') {
            $query->whereMonth('attended_at', now()->month)->whereYear('attended_at', now()->year);
        }

        $records = $query->orderByDesc('attended_at')->get();
        $grouped = $records->groupBy(function ($r) {
            return \Carbon\Carbon::parse($r->attended_at)->format('d M Y');
        });

        return view('admin.reports.attendance', compact('grouped', 'filter'));
    }

    public function paymentStatus(Request $request)
    {
        $status = $request->get('status', 'paid');

        $payments = Payment::with(['subscription.user', 'subscription.membership'])
            ->where('status', $status)
            ->orderByDesc('updated_at')
            ->get();

        return view('admin.reports.payment-status', compact('payments', 'status'));
    }
    public function corporate(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        $monthDate = \Carbon\Carbon::createFromFormat('Y-m', $month);

        $companies = User::where('role', 'client')
            ->where('is_corporate', true)
            ->pluck('company_name')
            ->unique()
            ->filter();

        $report = [];

        foreach ($companies as $company) {
            $clientIds = User::where('role', 'client')
                ->where('is_corporate', true)
                ->where('company_name', $company)
                ->pluck('id');

            $today = Attendance::whereIn('user_id', $clientIds)
                ->whereDate('attended_at', today())
                ->count();

            $week = Attendance::whereIn('user_id', $clientIds)
                ->whereBetween('attended_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->count();

            $monthAttendance = Attendance::whereIn('user_id', $clientIds)
                ->whereMonth('attended_at', $monthDate->month)
                ->whereYear('attended_at', $monthDate->year)
                ->get()
                ->groupBy('session_slot');

            $report[$company] = [
                'member_count' => $clientIds->count(),
                'today' => $today,
                'week' => $week,
                'month_total' => $monthAttendance->flatten()->count(),
                'morning' => $monthAttendance->get('morning', collect())->count(),
                'midday' => $monthAttendance->get('midday', collect())->count(),
                'evening' => $monthAttendance->get('evening', collect())->count(),
            ];
        }

        $grandTotal = collect($report)->sum('month_total');

        return view('admin.reports.corporate', compact('report', 'grandTotal', 'month'));
    }
    public function corporateAttendance(Request $request)
    {
        $company = $request->get('company');
        $period = $request->get('period', 'today'); // today, week, month
        $slot = $request->get('slot'); // optional: morning, midday, evening
        $month = $request->get('month', now()->format('Y-m'));
        $monthDate = \Carbon\Carbon::createFromFormat('Y-m', $month);

        $clientIds = User::where('role', 'client')
            ->where('is_corporate', true)
            ->where('company_name', $company)
            ->pluck('id');

        $query = Attendance::with('client')->whereIn('user_id', $clientIds);

        if ($period === 'today') {
            $query->whereDate('attended_at', today());
        } elseif ($period === 'week') {
            $query->whereBetween('attended_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($period === 'month') {
            $query->whereMonth('attended_at', $monthDate->month)->whereYear('attended_at', $monthDate->year);
        }

        if ($slot) {
            $query->where('session_slot', $slot);
        }

        $records = $query->orderByDesc('attended_at')->get();

        return view('admin.reports.corporate-attendance', compact('records', 'company', 'period', 'slot', 'month'));
    }
}