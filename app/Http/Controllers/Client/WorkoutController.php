<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\WorkoutAssignment;

class WorkoutController extends Controller
{
    public function show($id)
    {
        $assignment = WorkoutAssignment::where('client_id', auth()->id())
            ->with('workout.trainer')
            ->findOrFail($id);

        return view('client.workout-detail', compact('assignment'));
    }
}