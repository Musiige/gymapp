<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

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

        return view('trainer.client-detail', compact('client', 'attendance'));
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
}