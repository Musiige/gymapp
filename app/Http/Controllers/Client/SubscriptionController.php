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
            ->whereIn('status', ['active', 'pending'])
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

            Subscription::where('user_id', Auth::id())
                ->whereIn('status', ['active', 'pending'])
                ->update(['status' => 'expired']);
        }

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

        return redirect()->route('client.dashboard')
            ->with('success', 'Package selected successfully. Please complete your payment.');
    }
}