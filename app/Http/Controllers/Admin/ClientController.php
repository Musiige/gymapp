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

        $clients = User::where('role', 'client')
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

        return view('admin.clients', compact('clients', 'search'));
    }

    public function show($id)
    {
        $client = User::where('role', 'client')
            ->with([
                'subscriptions.membership',
                'subscriptions.payment',
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
}