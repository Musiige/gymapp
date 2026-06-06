<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    public function index()
    {
        $memberships = Membership::all();
        $activeSubscription = Subscription::where('user_id', Auth::id())
            ->where('status', 'active')
            ->with('membership')
            ->first();

        return view('client.subscription', compact('memberships', 'activeSubscription'));
    }

   public function store(Request $request)
{
    $request->validate([
        'membership_id' => ['required', 'exists:memberships,id'],
    ]);

    $membership = Membership::findOrFail($request->membership_id);

    // Cancel any existing active or pending subscriptions
    Subscription::where('user_id', Auth::id())
        ->whereIn('status', ['active', 'pending'])
        ->update(['status' => 'expired']);

    $startDate = Carbon::today();
    $endDate   = Carbon::today()->addDays($membership->duration_days);

    Subscription::create([
        'user_id'       => Auth::id(),
        'membership_id' => $membership->id,
        'start_date'    => $startDate,
        'end_date'      => $endDate,
        'status'        => 'pending',
    ]);

    return redirect()->route('client.dashboard')
        ->with('success', 'Package selected successfully. Please complete your payment.');
}}