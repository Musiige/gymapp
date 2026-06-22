<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
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
                      ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->with(['subscriptions' => function ($q) {
                $q->latest()->with(['membership', 'payment']);
            }])
            ->get();

        return view('trainer.clients', compact('clients', 'search'));
    }
    public function show($id)
    {
        $client = User::where('role', 'client')
            ->with([
                'subscriptions.membership',
                'subscriptions.payment',
            ])
            ->findOrFail($id);

        $attendance = \App\Models\Attendance::where('user_id', $id)
            ->latest('attended_at')
            ->get();

        return view('trainer.client-detail', compact('client', 'attendance'));
    }
}