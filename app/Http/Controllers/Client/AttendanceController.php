<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index()
    {
        $records = Attendance::where('user_id', Auth::id())
            ->orderByDesc('attended_at')
            ->get();

        $grouped = $records->groupBy(function ($r) {
            return \Carbon\Carbon::parse($r->attended_at)->format('d M Y');
        });

        return view('client.attendance', compact('grouped'));
    }
}