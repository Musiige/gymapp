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
            ->where('attended_at', '>=', now()->subDays(30))
            ->orderByDesc('attended_at')
            ->simplePaginate(20);

        $grouped = $records->getCollection()->groupBy(function ($r) {
            return \Carbon\Carbon::parse($r->attended_at)->format('d M Y');
        });

        return view('client.attendance', compact('grouped', 'records'));
    }
}