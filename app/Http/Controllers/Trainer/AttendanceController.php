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

        $clientIds = User::where('role', 'client')->pluck('id');
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

        // One check-in per day per client — regardless of slot or who marked it
        $already = Attendance::where('user_id', $request->client_id)
            ->whereDate('attended_at', today())
            ->first();

        if ($already) {
            $msg = $already->marked_by === 'client'
                ? 'This client already checked themselves in today.'
                : 'Attendance already marked for this client today.';
            return back()->with('error', $msg);
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

    public function update(Request $request, $id)
    {
        $request->validate([
            'session_slot' => ['required', 'in:morning,midday,evening'],
        ]);

        $record = Attendance::findOrFail($id);
        $record->update(['session_slot' => $request->session_slot]);

        return back()->with('success', 'Session updated successfully.');
    }

    public function destroy($id)
    {
        $record = Attendance::findOrFail($id);
        $record->delete();

        return back()->with('success', 'Attendance record deleted.');
    }
}