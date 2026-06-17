<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Workout;
use App\Models\WorkoutAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkoutController extends Controller
{
    public function index()
    {
        $workouts = Workout::where('trainer_id', Auth::id())
            ->with('assignments.client')
            ->latest()
            ->get();

        $clients = User::where('role', 'client')->get();

        return view('trainer.workouts', compact('workouts', 'clients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
        ]);

        Workout::create([
            'trainer_id'  => Auth::id(),
            'title'       => $request->title,
            'description' => $request->description,
        ]);

        return back()->with('success', 'Workout created successfully.');
    }

    public function assign(Request $request)
    {
        $request->validate([
            'workout_id' => ['required', 'exists:workouts,id'],
            'client_id'  => ['required', 'exists:users,id'],
        ]);

        $already = WorkoutAssignment::where('workout_id', $request->workout_id)
            ->where('client_id', $request->client_id)
            ->first();

        if ($already) {
            return back()->with('error', 'This workout is already assigned to this client.');
        }

        WorkoutAssignment::create([
            'workout_id' => $request->workout_id,
            'client_id'  => $request->client_id,
        ]);

        return back()->with('success', 'Workout assigned successfully.');
    }

    public function edit($id)
    {
        $workout = Workout::where('trainer_id', Auth::id())->findOrFail($id);
        $clients = User::where('role', 'client')->get();
        return view('trainer.workout-edit', compact('workout', 'clients'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
        ]);

        $workout = Workout::where('trainer_id', Auth::id())->findOrFail($id);
        $workout->update([
            'title'       => $request->title,
            'description' => $request->description,
        ]);

        return redirect()->route('trainer.workouts')
            ->with('success', 'Workout updated successfully.');
    }

    public function destroy($id)
    {
        $workout = Workout::where('trainer_id', Auth::id())->findOrFail($id);
        WorkoutAssignment::where('workout_id', $workout->id)->delete();
        $workout->delete();

        return redirect()->route('trainer.workouts')
            ->with('success', 'Workout deleted.');
    }
}