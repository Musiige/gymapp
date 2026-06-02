<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/qr', function () {
    return view('qr');
})->name('qr');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/client/dashboard', function () {
        abort_unless(Auth::user()->role === 'client', 403);
        return view('client.dashboard');
    })->name('client.dashboard');

    Route::get('/client/subscription', [App\Http\Controllers\Client\SubscriptionController::class, 'index'])
    ->name('client.subscription');

Route::post('/client/subscription', [App\Http\Controllers\Client\SubscriptionController::class, 'store'])
    ->name('client.subscription.store');

    Route::get('/trainer/dashboard', function () {
        abort_unless(Auth::user()->role === 'trainer', 403);
        return view('trainer.dashboard');
    })->name('trainer.dashboard');

    // Trainer routes
Route::get('/trainer/clients', [App\Http\Controllers\Trainer\ClientController::class, 'index'])
    ->name('trainer.clients');

Route::get('/trainer/attendance', [App\Http\Controllers\Trainer\AttendanceController::class, 'index'])
    ->name('trainer.attendance');

Route::post('/trainer/attendance', [App\Http\Controllers\Trainer\AttendanceController::class, 'store'])
    ->name('trainer.attendance.store');

Route::get('/trainer/workouts', [App\Http\Controllers\Trainer\WorkoutController::class, 'index'])
    ->name('trainer.workouts');

Route::post('/trainer/workouts', [App\Http\Controllers\Trainer\WorkoutController::class, 'store'])
    ->name('trainer.workouts.store');

Route::post('/trainer/workouts/assign', [App\Http\Controllers\Trainer\WorkoutController::class, 'assign'])
    ->name('trainer.workouts.assign');

    Route::get('/admin/dashboard', function () {
        abort_unless(Auth::user()->role === 'admin', 403);
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

require __DIR__.'/auth.php';