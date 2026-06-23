<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\AllowanceEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AllowanceController extends Controller
{
    public function index()
    {
        $entries = AllowanceEntry::where('trainer_id', Auth::id())
            ->orderByDesc('date')
            ->orderByDesc('created_at')
            ->get();

        $balance = $entries->sum(function ($entry) {
            return $entry->type === 'demand' ? $entry->amount : -$entry->amount;
        });

        return view('trainer.allowances', compact('entries', 'balance'));
    }

    public function history()
    {
        $entries = AllowanceEntry::where('trainer_id', Auth::id())
            ->orderByDesc('date')
            ->orderByDesc('created_at')
            ->get();

        $grouped = $entries->groupBy(function ($entry) {
            return \Carbon\Carbon::parse($entry->date)->format('F Y');
        });

        return view('trainer.allowances-history', compact('grouped'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date'   => ['required', 'date'],
            'type'   => ['required', 'in:demand,payment'],
            'reason' => ['nullable', 'required_if:type,demand', 'string', 'max:100'],
            'amount' => ['required', 'numeric', 'min:1'],
        ]);

        AllowanceEntry::create([
            'trainer_id' => Auth::id(),
            'date'       => $request->date,
            'type'       => $request->type,
            'reason'     => $request->type === 'demand' ? $request->reason : null,
            'amount'     => $request->amount,
        ]);

        return back()->with('success', 'Entry added successfully.');
    }

    public function update(Request $request, $id)
    {
        $entry = AllowanceEntry::where('trainer_id', Auth::id())->findOrFail($id);

        $request->validate([
            'date'   => ['required', 'date'],
            'type'   => ['required', 'in:demand,payment'],
            'reason' => ['nullable', 'required_if:type,demand', 'string', 'max:100'],
            'amount' => ['required', 'numeric', 'min:1'],
        ]);

        $entry->update([
            'date'   => $request->date,
            'type'   => $request->type,
            'reason' => $request->type === 'demand' ? $request->reason : null,
            'amount' => $request->amount,
        ]);

        return back()->with('success', 'Entry updated successfully.');
    }

    public function destroy($id)
    {
        $entry = AllowanceEntry::where('trainer_id', Auth::id())->findOrFail($id);
        $entry->delete();

        return back()->with('success', 'Entry deleted.');
    }
    public function destroyAll()
    {
        AllowanceEntry::where('trainer_id', Auth::id())->delete();

        return redirect()->route('trainer.allowances')->with('success', 'All ledger entries deleted.');
    }
}