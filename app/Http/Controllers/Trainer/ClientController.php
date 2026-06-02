<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\User;

class ClientController extends Controller
{
    public function index()
    {
        $clients = User::where('role', 'client')
            ->with(['subscriptions.membership' => function ($query) {
                $query->latest();
            }])
            ->get();

        return view('trainer.clients', compact('clients'));
    }
}