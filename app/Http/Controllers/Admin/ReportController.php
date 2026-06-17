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
}