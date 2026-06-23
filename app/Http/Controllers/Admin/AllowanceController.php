<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AllowanceEntry;
use App\Models\User;

class AllowanceController extends Controller
{
    public function index()
    {
        $trainers = User::where('role', 'trainer')->get();

        $trainerBalances = $trainers->map(function ($trainer) {
            $entries = AllowanceEntry::where('trainer_id', $trainer->id)->get();
            $balance = $entries->sum(function ($entry) {
                return $entry->type === 'demand' ? $entry->amount : -$entry->amount;
            });

            return [
                'trainer' => $trainer,
                'balance' => $balance,
                'entry_count' => $entries->count(),
            ];
        });

        return view('admin.allowances', compact('trainerBalances'));
    }

    public function show($trainerId)
    {
        $trainer = User::where('role', 'trainer')->findOrFail($trainerId);

        $entries = AllowanceEntry::where('trainer_id', $trainerId)
            ->orderByDesc('date')
            ->orderByDesc('created_at')
            ->get();

        $balance = $entries->sum(function ($entry) {
            return $entry->type === 'demand' ? $entry->amount : -$entry->amount;
        });

        $grouped = $entries->groupBy(function ($entry) {
            return \Carbon\Carbon::parse($entry->date)->format('F Y');
        });

        return view('admin.allowance-detail', compact('trainer', 'grouped', 'balance'));
    }
}