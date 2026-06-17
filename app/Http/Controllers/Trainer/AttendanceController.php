<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index()
    {
        $clients = User::where('role', 'client')->get();

       $clientIds = \App\Models\User::where('role', 'client')->pluck('id');
$todayAttendance = Attendance::whereIn('user_id', $clientIds)
    ->whereDate('attended_at', today())
    ->with('client')
    ->get();

        return view('trainer.attendance', compact('clients', 'todayAttendance'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id'    => ['required', 'exists:users,id'],
            'session_slot' => ['required', 'in:morning,midday,evening'],
        ]);

        $already = Attendance::where('user_id', $request->client_id)
            ->where('trainer_id', Auth::id())
            ->where('session_slot', $request->session_slot)
            ->whereDate('attended_at', today())
            ->first();

        if ($already) {
            return back()->with('error', 'Attendance already marked for this client in this session today.');
        }

        $alreadySelf = Attendance::where('user_id', $request->client_id)
    ->where('session_slot', $request->session_slot)
    ->whereDate('attended_at', today())
    ->where('marked_by', 'client')
    ->first();

if ($alreadySelf) {
    return back()->with('error', 'This client already checked in themselves for this session.');
}

      Attendance::create([
    'user_id'      => $request->client_id,
    'trainer_id'   => Auth::id(),
    'session_slot' => $request->session_slot,
    'attended_at'  => now(),
    'marked_by'    => 'trainer',
]);

        return back()->with('success', 'Attendance marked successfully.');
    }
}