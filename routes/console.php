<?php
use Illuminate\Support\Facades\Schedule;

Schedule::command('gym:expire-subscriptions')->dailyAt('00:05');