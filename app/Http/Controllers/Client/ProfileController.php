<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $subscription = Subscription::where('user_id', $user->id)
            ->with(['membership', 'payment'])
            ->latest()
            ->first();

        $totalAttendance = Attendance::where('user_id', $user->id)->count();

        $monthAttendance = Attendance::where('user_id', $user->id)
            ->whereMonth('attended_at', now()->month)
            ->count();

        return view('client.profile', compact(
            'user', 'subscription', 'totalAttendance', 'monthAttendance'
        ));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
        ]);

        Auth::user()->update([
            'name'  => $request->name,
            'phone' => $request->phone,
        ]);

        return back()->with('success', 'Profile updated successfully.');
    }
}