<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\SubscriptionChange;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $allClients = User::where('role', 'client')
            ->with(['subscriptions' => function ($q) {
                $q->latest()->with(['membership', 'payment']);
            }])
            ->get();

        $regularClients = $allClients->where('is_corporate', false);
        $corporateClients = $allClients->where('is_corporate', true)->groupBy('company_name');

        return view('trainer.clients', compact('regularClients', 'corporateClients'));
    }
  public function show($id)
    {
        $client = User::where('role', 'client')
            ->with([
                'subscriptions.membership',
                'subscriptions.payment',
                'workoutAssignments.workout.trainer',
            ])
            ->findOrFail($id);
        $attendance = \App\Models\Attendance::where('user_id', $id)
            ->latest('attended_at')
            ->get();

        $memberships = \App\Models\Membership::where('name', '!=', 'Corporate')->get();

        return view('trainer.client-detail', compact('client', 'attendance', 'memberships'));
    }
    public function subscriptions($id)
    {
        $client = User::where('role', 'client')
            ->with(['subscriptions.membership', 'subscriptions.payment'])
            ->findOrFail($id);

        $subscriptions = $client->subscriptions->sortByDesc('created_at');

        return view('trainer.client-subscriptions', compact('client', 'subscriptions'));
    }
    public function attendance($id)
    {
        $client = User::where('role', 'client')->findOrFail($id);

        $records = \App\Models\Attendance::where('user_id', $id)
            ->orderByDesc('attended_at')
            ->get();

        $grouped = $records->groupBy(function ($r) {
            return \Carbon\Carbon::parse($r->attended_at)->format('d M Y');
        });

        return view('trainer.client-attendance', compact('client', 'grouped'));
    }
    public function assignPackage(\Illuminate\Http\Request $request, $id)
    {
        $request->validate([
            'membership_id' => ['required', 'exists:memberships,id'],
        ]);

        $client = \App\Models\User::where('role', 'client')->findOrFail($id);
        $membership = \App\Models\Membership::findOrFail($request->membership_id);

        $existing = \App\Models\Subscription::where('user_id', $id)
            ->whereNotIn('status', ['expired', 'changed'])
            ->latest()
            ->first();

        if ($existing && $existing->membership_id != $membership->id) {
            \App\Models\SubscriptionChange::create([
                'user_id'           => $id,
                'old_membership_id' => $existing->membership_id,
                'new_membership_id' => $membership->id,
                'changed_by'        => 'trainer',
                'changed_at'        => now(),
            ]);
        }

        \App\Models\Subscription::where('user_id', $id)
            ->whereNotIn('status', ['expired', 'changed'])
            ->update(['status' => 'changed']);

        $startDate = \Carbon\Carbon::today();
        $endDate   = $membership->duration_days === 1
            ? \Carbon\Carbon::today()->endOfDay()
            : \Carbon\Carbon::today()->addDays($membership->duration_days);

        \App\Models\Subscription::create([
            'user_id'       => $id,
            'membership_id' => $membership->id,
            'start_date'    => $startDate,
            'end_date'      => $endDate,
            'status'        => 'pending',
        ]);

        return back()->with('success', $membership->name . ' package assigned to ' . $client->name . '.');
    }
}