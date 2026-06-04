<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Workout;
use App\Models\WorkoutAssignment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendMonthlyReports extends Command
{
    protected $signature   = 'gym:send-monthly-reports';
    protected $description = 'Generate and email monthly PDF reports to admin, trainers, and clients';

    public function handle(): void
    {
        $month = now()->subMonth()->format('F Y');
        $startOfMonth = now()->subMonth()->startOfMonth();
        $endOfMonth   = now()->subMonth()->endOfMonth();

        $this->sendAdminReport($month, $startOfMonth, $endOfMonth);
        $this->sendTrainerReports($month, $startOfMonth, $endOfMonth);
        $this->sendClientReports($month, $startOfMonth, $endOfMonth);

        $this->info('Monthly reports sent successfully.');
    }

    private function sendAdminReport($month, $startOfMonth, $endOfMonth): void
    {
        $admins = User::where('role', 'admin')->get();

        $totalRevenue = Payment::where('status', '!=', 'unpaid')
            ->whereBetween('paid_at', [$startOfMonth, $endOfMonth])
            ->sum('amount_paid');

        $totalClients = User::where('role', 'client')->count();

        $totalAttendance = Attendance::whereBetween('attended_at', [$startOfMonth, $endOfMonth])->count();

        $attendanceBySlot = Attendance::whereBetween('attended_at', [$startOfMonth, $endOfMonth])
            ->selectRaw('session_slot, count(*) as total')
            ->groupBy('session_slot')
            ->pluck('total', 'session_slot')
            ->toArray();

        $paidCount     = Payment::where('status', 'paid')->count();
        $halfPaidCount = Payment::where('status', 'half-paid')->count();
        $unpaidCount   = Payment::where('status', 'unpaid')->count();

        $subscriptions = Subscription::whereBetween('start_date', [$startOfMonth, $endOfMonth])
            ->with(['user', 'membership', 'payment'])
            ->get();

        $pdf = Pdf::loadView('reports.monthly-admin', compact(
            'month', 'totalRevenue', 'totalClients', 'totalAttendance',
            'attendanceBySlot', 'paidCount', 'halfPaidCount', 'unpaidCount',
            'subscriptions'
        ));

        foreach ($admins as $admin) {
            Mail::send([], [], function ($message) use ($admin, $pdf, $month) {
                $message->to($admin->email, $admin->name)
                    ->subject('Monthly Admin Report — ' . $month)
                    ->text('Please find attached your monthly admin report for ' . $month . '.')
                    ->attachData($pdf->output(), 'admin-report-' . now()->subMonth()->format('Y-m') . '.pdf', [
                        'mime' => 'application/pdf',
                    ]);
            });
            sleep(2);
        }

        $this->info('Admin report sent.');
    }

    private function sendTrainerReports($month, $startOfMonth, $endOfMonth): void
    {
        $trainers = User::where('role', 'trainer')->get();

        foreach ($trainers as $trainer) {
            $attendance = Attendance::where('trainer_id', $trainer->id)
                ->whereBetween('attended_at', [$startOfMonth, $endOfMonth])
                ->with('client')
                ->get();

            $workouts = Workout::where('trainer_id', $trainer->id)
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->get();

            $pdf = Pdf::loadView('reports.monthly-trainer', compact(
                'month', 'trainer', 'attendance', 'workouts'
            ));

            Mail::send([], [], function ($message) use ($trainer, $pdf, $month) {
                $message->to($trainer->email, $trainer->name)
                    ->subject('Monthly Trainer Report — ' . $month)
                    ->text('Please find attached your monthly trainer report for ' . $month . '.')
                    ->attachData($pdf->output(), 'trainer-report-' . now()->subMonth()->format('Y-m') . '.pdf', [
                        'mime' => 'application/pdf',
                    ]);
            });
            sleep(2);
        }

        $this->info('Trainer reports sent.');
    }

    private function sendClientReports($month, $startOfMonth, $endOfMonth): void
    {
        $clients = User::where('role', 'client')->get();

        foreach ($clients as $client) {
            $attendance = Attendance::where('user_id', $client->id)
                ->whereBetween('attended_at', [$startOfMonth, $endOfMonth])
                ->get();

            $subscription = Subscription::where('user_id', $client->id)
                ->with(['membership', 'payment'])
                ->latest()
                ->first();

            $workouts = WorkoutAssignment::where('client_id', $client->id)
                ->whereBetween('assigned_at', [$startOfMonth, $endOfMonth])
                ->with('workout')
                ->get();

            $user = $client;

            $pdf = Pdf::loadView('reports.monthly-client', compact(
                'month', 'user', 'attendance', 'subscription', 'workouts'
            ));

            $attendanceCount = $attendance->count();

            $pdf = Pdf::loadView('reports.monthly-client', compact(
                'month', 'user', 'attendance', 'subscription', 'workouts', 'attendanceCount'
            ));

            Mail::send([], [], function ($message) use ($client, $pdf, $month) {
                $message->to($client->email, $client->name)
                    ->subject('Monthly Report — ' . $month)
                    ->text('Please find attached your monthly client report for ' . $month . '.')
                    ->attachData($pdf->output(), 'client-report-' . now()->subMonth()->format('Y-m') . '.pdf', [
                        'mime' => 'application/pdf',
                    ]);
            });
            sleep(2);
        }

        $this->info('Client reports sent.');
    }
}