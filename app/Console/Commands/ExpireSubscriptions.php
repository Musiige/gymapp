<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ExpireSubscriptions extends Command
{
    protected $signature   = 'gym:expire-subscriptions';
    protected $description = 'Mark subscriptions as expired when their end_date has passed';

    public function handle()
    {
        $count = Subscription::whereIn('status', ['active', 'pending'])
            ->where('end_date', '<', Carbon::today())
            ->update([
                'status'         => 'expired',
                'access_granted' => false,
            ]);

        $this->info("Expired {$count} subscription(s).");

        return Command::SUCCESS;
    }
}