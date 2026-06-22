<?php
namespace App\Http\Controllers\Client;
use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\WorkoutAssignment;
class WorkoutController extends Controller
{
    public function show($id)
    {
        $activeSubscription = Subscription::where('user_id', auth()->id())
            ->where('status', 'active')
            ->first();

      if (!$activeSubscription) {
            return redirect()->route('client.dashboard')
                ->with('error', 'You need an active membership to view workouts.');
        }

        if (!$activeSubscription->access_granted) {
            return redirect()->route('client.dashboard')
                ->with('error', 'Your gym access has not been activated yet.');
        }

        $assignment = WorkoutAssignment::where('client_id', auth()->id())
            ->with('workout.trainer')
            ->findOrFail($id);
        return view('client.workout-detail', compact('assignment'));
    }
}