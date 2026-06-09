<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\Subscription;
use App\Models\SubscriptionChange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    public function index()
    {
        $memberships = Membership::all();
        $activeSubscription = Subscription::where('user_id', Auth::id())
            ->whereIn('status', ['active', 'pending'])
            ->with('membership')
            ->latest()
            ->first();

        return view('client.subscription', compact('memberships', 'activeSubscription'));
    }

    public function confirm(Request $request)
    {
        $request->validate([
            'membership_id' => ['required', 'exists:memberships,id'],
        ]);

        $newMembership = Membership::findOrFail($request->membership_id);

        $activeSubscription = Subscription::where('user_id', Auth::id())
            ->whereIn('status', ['active', 'pending'])
            ->with('membership')
            ->latest()
            ->first();

        if (!$activeSubscription || $activeSubscription->membership_id == $newMembership->id) {
            return $this->processStore($newMembership->id);
        }

        return view('client.subscription-confirm', compact('activeSubscription', 'newMembership'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'membership_id' => ['required', 'exists:memberships,id'],
        ]);

        return $this->processStore($request->membership_id);
    }

   private function processStore($membershipId)
{
    $membership = Membership::findOrFail($membershipId);

    $activeSubscription = Subscription::where('user_id', Auth::id())
        ->whereNotIn('status', ['expired', 'changed'])
        ->latest()
        ->first();

    if ($activeSubscription && $activeSubscription->membership_id != $membership->id) {
        SubscriptionChange::create([
            'user_id'           => Auth::id(),
            'old_membership_id' => $activeSubscription->membership_id,
            'new_membership_id' => $membership->id,
            'changed_by'        => 'client',
            'changed_at'        => now(),
        ]);
    }

    // Force mark all non-expired subscriptions as changed
    Subscription::where('user_id', Auth::id())
        ->whereNotIn('status', ['expired', 'changed'])
        ->update(['status' => 'changed']);

    $startDate = Carbon::today();
    $endDate   = $membership->duration_days === 1
        ? Carbon::today()->endOfDay()
        : Carbon::today()->addDays($membership->duration_days);

    Subscription::create([
        'user_id'       => Auth::id(),
        'membership_id' => $membership->id,
        'start_date'    => $startDate,
        'end_date'      => $endDate,
        'status'        => 'pending',
    ]);

   $newSubscription = Subscription::where('user_id', Auth::id())
    ->latest()
    ->first();

return redirect()->route('client.payment', $newSubscription->id)
    ->with('success', 'Package selected successfully. Please complete your payment.');
}
    public function checkin(Request $request)
{
    $request->validate([
        'session_slot' => ['required', 'in:morning,midday,evening'],
    ]);

    $activeSubscription = Subscription::where('user_id', Auth::id())
        ->where('status', 'active')
        ->first();

    if (!$activeSubscription) {
        return back()->with('error', 'You need an active membership to check in.');
    }

   $already = \App\Models\Attendance::where('user_id', Auth::id())
    ->where('session_slot', $request->session_slot)
    ->whereDate('attended_at', today())
    ->first();

if ($already) {
    $msg = $already->marked_by === 'trainer'
        ? 'Your trainer has already marked your attendance for this session.'
        : 'You have already checked in for this session today.';
    return back()->with('error', $msg);
}

    \App\Models\Attendance::create([
        'user_id'      => Auth::id(),
        'trainer_id'   => Auth::id(),
        'session_slot' => $request->session_slot,
        'attended_at'  => now(),
        'marked_by'    => 'client',
    ]);

    return back()->with('success', 'Check-in successful. Welcome to your session! 💪');
}
}