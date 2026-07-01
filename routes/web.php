<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});
Route::get('/qr', function () {
    return view('qr');
})->name('qr');

Route::get('/dashboard', function () {
    $role = auth()->user()->role;
    return match($role) {
        'admin'   => redirect('/admin/dashboard'),
        'trainer' => redirect('/trainer/dashboard'),
        default   => redirect('/client/dashboard'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

// MTN MoMo webhook — must stay OUTSIDE the 'auth' middleware group.
// Protected instead by the {secret} segment (see MOMO_CALLBACK_SECRET in .env).
Route::post('/momo/callback/{secret}', [App\Http\Controllers\Client\PaymentController::class, 'momoCallback'])
    ->name('momo.callback');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

   Route::get('/client/dashboard', function () {
    abort_unless(Auth::user()->role === 'client', 403);
    if (!Auth::user()->onboarded) {
        return redirect()->route('client.welcome');
    }
    return view('client.dashboard');
})->name('client.dashboard');

   Route::get('/client/subscription', [App\Http\Controllers\Client\SubscriptionController::class, 'index'])
    ->name('client.subscription');

Route::post('/client/subscription/confirm', [App\Http\Controllers\Client\SubscriptionController::class, 'confirm'])
    ->name('client.subscription.confirm');

Route::post('/client/subscription', [App\Http\Controllers\Client\SubscriptionController::class, 'store'])
    ->name('client.subscription.store');

    Route::get('/client/payment/{subscription}', [App\Http\Controllers\Client\PaymentController::class, 'show'])
    ->name('client.payment');

Route::post('/client/payment/{subscription}', [App\Http\Controllers\Client\PaymentController::class, 'process'])
    ->name('client.payment.process');

// Polling fallback for MoMo Request-to-Pay status (used by client.payment view's JS)
Route::get('/client/payment/{subscription}/status', [App\Http\Controllers\Client\PaymentController::class, 'checkStatus'])
    ->name('client.payment.status');

Route::get('/trainer/dashboard', [App\Http\Controllers\Trainer\DashboardController::class, 'index'])
    ->name('trainer.dashboard');

    // Trainer routes
Route::get('/trainer/clients', [App\Http\Controllers\Trainer\ClientController::class, 'index'])
    ->name('trainer.clients');

Route::get('/trainer/attendance', [App\Http\Controllers\Trainer\AttendanceController::class, 'index'])
    ->name('trainer.attendance');

Route::post('/trainer/attendance', [App\Http\Controllers\Trainer\AttendanceController::class, 'store'])
    ->name('trainer.attendance.store');

    Route::put('/trainer/attendance/{id}', [App\Http\Controllers\Trainer\AttendanceController::class, 'update'])
    ->name('trainer.attendance.update');

Route::delete('/trainer/attendance/{id}', [App\Http\Controllers\Trainer\AttendanceController::class, 'destroy'])
    ->name('trainer.attendance.destroy');

Route::get('/trainer/workouts', [App\Http\Controllers\Trainer\WorkoutController::class, 'index'])
    ->name('trainer.workouts');

Route::post('/trainer/workouts', [App\Http\Controllers\Trainer\WorkoutController::class, 'store'])
    ->name('trainer.workouts.store');

Route::post('/trainer/workouts/assign', [App\Http\Controllers\Trainer\WorkoutController::class, 'assign'])
    ->name('trainer.workouts.assign');

   Route::get('/admin/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])
    ->name('admin.dashboard');

   Route::post('/client/fcm-token', function (Illuminate\Http\Request $request) {
    $request->validate(['token' => 'required|string']);
    $user = \App\Models\User::find(Auth::id());
    $user->fcm_token = $request->token;
    $user->save();
    return response()->json(['success' => true]);
})->name('client.fcm.token');

Route::get('/admin/announcements', [App\Http\Controllers\Admin\AnnouncementController::class, 'index'])
    ->name('admin.announcements');

Route::post('/admin/announcements', [App\Http\Controllers\Admin\AnnouncementController::class, 'send'])
    ->name('admin.announcements.send');
});

Route::get('/client/profile', [App\Http\Controllers\Client\ProfileController::class, 'index'])
    ->name('client.profile');

Route::post('/client/profile', [App\Http\Controllers\Client\ProfileController::class, 'update'])
    ->name('client.profile.update');

    Route::get('/admin/attendance/session', [App\Http\Controllers\Admin\DashboardController::class, 'sessionAttendance'])
    ->name('admin.attendance.session');

    Route::get('/admin/reports/revenue', [App\Http\Controllers\Admin\ReportController::class, 'revenue'])
    ->name('admin.reports.revenue');

Route::get('/admin/reports/attendance', [App\Http\Controllers\Admin\ReportController::class, 'attendance'])
    ->name('admin.reports.attendance');

Route::get('/admin/reports/payment-status', [App\Http\Controllers\Admin\ReportController::class, 'paymentStatus'])
    ->name('admin.reports.payment-status');

    Route::delete('/client/subscription/{subscription}/cancel', [App\Http\Controllers\Client\SubscriptionController::class, 'cancel'])
    ->name('client.subscription.cancel');

    Route::get('/trainer/attendance/session', [App\Http\Controllers\Trainer\DashboardController::class, 'sessionAttendance'])
    ->name('trainer.attendance.session');

    Route::get('/trainer/reports/attendance', [App\Http\Controllers\Trainer\DashboardController::class, 'attendanceHistory'])
    ->name('trainer.reports.attendance');

    Route::post('/admin/clients/{id}/corporate', [App\Http\Controllers\Admin\ClientController::class, 'updateCorporate'])
    ->name('admin.clients.corporate');

    Route::get('/admin/reports/corporate', [App\Http\Controllers\Admin\ReportController::class, 'corporate'])
    ->name('admin.reports.corporate');

    Route::get('/admin/reports/corporate/attendance', [App\Http\Controllers\Admin\ReportController::class, 'corporateAttendance'])
    ->name('admin.reports.corporate.attendance');

    Route::post('/admin/subscription/{subscription}/toggle-access', [App\Http\Controllers\Admin\PaymentController::class, 'toggleAccess'])
    ->name('admin.subscription.toggle-access');

Route::post('/trainer/subscription/{subscription}/toggle-access', [App\Http\Controllers\Trainer\PaymentController::class, 'toggleAccess'])
    ->name('trainer.subscription.toggle-access');

    Route::get('/trainer/clients/{id}', [App\Http\Controllers\Trainer\ClientController::class, 'show'])
    ->name('trainer.clients.show');

    Route::delete('/trainer/workouts/assignment/{id}', [App\Http\Controllers\Trainer\WorkoutController::class, 'unassign'])
    ->name('trainer.workouts.unassign');

    Route::delete('/admin/announcements/{id}', [App\Http\Controllers\Admin\AnnouncementController::class, 'destroy'])
    ->name('admin.announcements.destroy');

    Route::get('/trainer/clients/{id}/subscriptions', [App\Http\Controllers\Trainer\ClientController::class, 'subscriptions'])
    ->name('trainer.clients.subscriptions');

    Route::get('/trainer/clients/{id}/attendance', [App\Http\Controllers\Trainer\ClientController::class, 'attendance'])
    ->name('trainer.clients.attendance');

    Route::get('/trainer/workouts/{id}/view', [App\Http\Controllers\Trainer\WorkoutController::class, 'show'])
    ->name('trainer.workouts.show');

    Route::get('/admin/clients/{id}/subscriptions', [App\Http\Controllers\Admin\ClientController::class, 'subscriptions'])
    ->name('admin.clients.subscriptions');

Route::get('/admin/clients/{id}/attendance', [App\Http\Controllers\Admin\ClientController::class, 'attendance'])
    ->name('admin.clients.attendance');

    Route::get('/admin/clients/{id}/changes', [App\Http\Controllers\Admin\ClientController::class, 'changes'])
    ->name('admin.clients.changes');

    Route::get('/trainer/allowances', [App\Http\Controllers\Trainer\AllowanceController::class, 'index'])
    ->name('trainer.allowances');

Route::post('/trainer/allowances', [App\Http\Controllers\Trainer\AllowanceController::class, 'store'])
    ->name('trainer.allowances.store');

Route::put('/trainer/allowances/{id}', [App\Http\Controllers\Trainer\AllowanceController::class, 'update'])
    ->name('trainer.allowances.update');

Route::delete('/trainer/allowances/{id}', [App\Http\Controllers\Trainer\AllowanceController::class, 'destroy'])
    ->name('trainer.allowances.destroy');

    Route::get('/trainer/allowances/history', [App\Http\Controllers\Trainer\AllowanceController::class, 'history'])
    ->name('trainer.allowances.history');

    Route::delete('/trainer/allowances/delete-all', [App\Http\Controllers\Trainer\AllowanceController::class, 'destroyAll'])
    ->name('trainer.allowances.destroy-all');

    Route::get('/admin/allowances', [App\Http\Controllers\Admin\AllowanceController::class, 'index'])
    ->name('admin.allowances');

Route::get('/admin/allowances/{trainerId}', [App\Http\Controllers\Admin\AllowanceController::class, 'show'])
    ->name('admin.allowances.show');

    Route::get('/admin/reports/subscriptions', [App\Http\Controllers\Admin\ReportController::class, 'subscriptions'])
    ->name('admin.reports.subscriptions');
    Route::get('/trainer/inbox', [App\Http\Controllers\Trainer\InboxController::class, 'index'])
    ->name('trainer.inbox');

    Route::get('/client/attendance', [App\Http\Controllers\Client\AttendanceController::class, 'index'])
    ->name('client.attendance');

Route::get('/client/payments', [App\Http\Controllers\Client\PaymentController::class, 'history'])
    ->name('client.payments');
Route::delete('/admin/clients/{id}', [App\Http\Controllers\Admin\ClientController::class, 'destroy'])
    ->name('admin.clients.destroy');

    Route::post('/admin/clients/{id}/assign-package', [App\Http\Controllers\Admin\ClientController::class, 'assignPackage'])
    ->name('admin.clients.assign-package');

Route::post('/trainer/clients/{id}/assign-package', [App\Http\Controllers\Trainer\ClientController::class, 'assignPackage'])
    ->name('trainer.clients.assign-package');

require __DIR__.'/auth.php';

Route::get('/admin/clients', [App\Http\Controllers\Admin\ClientController::class, 'index'])
    ->name('admin.clients');

Route::get('/admin/clients/{id}', [App\Http\Controllers\Admin\ClientController::class, 'show'])
    ->name('admin.clients.show');

    Route::post('/client/checkin', [App\Http\Controllers\Client\SubscriptionController::class, 'checkin'])
    ->name('client.checkin');

    Route::post('/admin/payment/{subscription}/mark-paid', [App\Http\Controllers\Admin\PaymentController::class, 'markPaid'])
    ->name('admin.payment.mark');

    Route::post('/admin/subscription/{subscription}/set-price', [App\Http\Controllers\Admin\PaymentController::class, 'setCustomPrice'])
    ->name('admin.subscription.set-price');

    Route::get('/client/workouts/{id}', [App\Http\Controllers\Client\WorkoutController::class, 'show'])
    ->name('client.workout.show');

    Route::post('/trainer/payment/{subscription}/mark-paid', [App\Http\Controllers\Trainer\PaymentController::class, 'markPaid'])
    ->name('trainer.payment.mark');

    Route::post('/admin/payment/{subscription}/void', [App\Http\Controllers\Admin\PaymentController::class, 'voidPayment'])
    ->name('admin.payment.void');

Route::post('/admin/payment/{subscription}/edit', [App\Http\Controllers\Admin\PaymentController::class, 'editPayment'])
    ->name('admin.payment.edit');

    Route::get('/trainer/workouts/{id}/edit', [App\Http\Controllers\Trainer\WorkoutController::class, 'edit'])
    ->name('trainer.workouts.edit');

Route::put('/trainer/workouts/{id}', [App\Http\Controllers\Trainer\WorkoutController::class, 'update'])
    ->name('trainer.workouts.update');

Route::delete('/trainer/workouts/{id}', [App\Http\Controllers\Trainer\WorkoutController::class, 'destroy'])
    ->name('trainer.workouts.destroy');

    Route::get('/client/inbox', [App\Http\Controllers\Client\InboxController::class, 'index'])
    ->name('client.inbox');

    Route::get('/admin/staff', [App\Http\Controllers\Admin\StaffController::class, 'index'])
    ->name('admin.staff');

Route::post('/admin/staff', [App\Http\Controllers\Admin\StaffController::class, 'store'])
    ->name('admin.staff.store');

Route::delete('/admin/staff/{id}', [App\Http\Controllers\Admin\StaffController::class, 'destroy'])
    ->name('admin.staff.destroy');

Route::post('/admin/staff/{id}/role', [App\Http\Controllers\Admin\StaffController::class, 'updateRole'])
    ->name('admin.staff.role');

    Route::get('/client/welcome', function () {
    return view('client.welcome');
})->name('client.welcome');

Route::post('/client/welcome/done', function () {
    \App\Models\User::where('id', Auth::id())->update(['onboarded' => true]);
    return redirect()->route('client.dashboard');
})->name('client.welcome.done');