<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\SubscriptionChange;
use App\Models\User;
use Illuminate\Http\Request;

class ClientController extends Controller
{
  public function index(Request $request)
    {
        $search = $request->get('search');

        $allClients = User::where('role', 'client')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->with([
                'subscriptions.membership',
                'subscriptions.payment',
            ])
            ->latest()
            ->get();

        $regularClients = $allClients->where('is_corporate', false);
        $corporateClients = $allClients->where('is_corporate', true)->groupBy('company_name');

        return view('admin.clients', compact('regularClients', 'corporateClients', 'search'));
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

        $attendance = Attendance::where('user_id', $id)
            ->with('trainer')
            ->latest('attended_at')
            ->get();

        $changes = SubscriptionChange::where('user_id', $id)
            ->with(['oldMembership', 'newMembership'])
            ->latest('changed_at')
            ->get();

        $totalSessions = $attendance->count();
        $totalPaid = $client->subscriptions->sum(function ($sub) {
            return $sub->payment->amount_paid ?? 0;
        });

        return view('admin.client-detail', compact(
            'client', 'attendance', 'changes', 'totalSessions', 'totalPaid'
        ));
    }

    public function subscriptions($id)
    {
        $client = User::where('role', 'client')
            ->with(['subscriptions.membership', 'subscriptions.payment'])
            ->findOrFail($id);

        $subscriptions = $client->subscriptions->sortByDesc('created_at');

        return view('admin.client-subscriptions', compact('client', 'subscriptions'));
    }

    public function attendance($id)
    {
        $client = User::where('role', 'client')->findOrFail($id);

        $records = Attendance::where('user_id', $id)
            ->with('trainer')
            ->orderByDesc('attended_at')
            ->get();

        $grouped = $records->groupBy(function ($r) {
            return \Carbon\Carbon::parse($r->attended_at)->format('d M Y');
        });

        return view('admin.client-attendance', compact('client', 'grouped'));
    }
    public function changes($id)
    {
        $client = User::where('role', 'client')->findOrFail($id);

        $changes = SubscriptionChange::where('user_id', $id)
            ->with(['oldMembership', 'newMembership'])
            ->latest('changed_at')
            ->get();

        return view('admin.client-changes', compact('client', 'changes'));
    }
    public function updateCorporate(Request $request, $id)
    {
        $request->validate([
            'is_corporate' => ['required', 'boolean'],
            'company_name' => ['nullable', 'required_if:is_corporate,1', 'string', 'max:100'],
        ]);

        $client = User::where('role', 'client')->findOrFail($id);

        $client->update([
            'is_corporate' => $request->is_corporate,
            'company_name' => $request->is_corporate ? $request->company_name : null,
        ]);

        // If marking as corporate, create a free active subscription
        if ($request->is_corporate) {
            $corporateMembership = \App\Models\Membership::firstOrCreate(
                ['name' => 'Corporate'],
                ['price' => 0, 'duration_days' => 36500] // ~100 years, effectively no expiry
            );

            \App\Models\Subscription::where('user_id', $client->id)
                ->whereNotIn('status', ['expired', 'changed'])
                ->update(['status' => 'changed']);

            \App\Models\Subscription::create([
                'user_id' => $client->id,
                'membership_id' => $corporateMembership->id,
                'start_date' => now(),
                'end_date' => now()->addYears(100),
                'status' => 'active',
            ]);
        }

        return back()->with('success', $request->is_corporate
            ? $client->name . ' is now marked as a corporate client.'
            : $client->name . ' corporate status removed.');
    }
}