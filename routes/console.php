<?php
use Illuminate\Support\Facades\Schedule;
Schedule::command('gym:send-payment-reminders')->dailyAt('08:00');
Schedule::command('momo:reconcile')->everyMinute()->withoutOverlapping();