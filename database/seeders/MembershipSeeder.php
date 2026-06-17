<?php

namespace Database\Seeders;

use App\Models\Membership;
use Illuminate\Database\Seeder;

class MembershipSeeder extends Seeder
{
    public function run(): void
    {
        $memberships = [
            [
                'name'          => 'Daily',
                'price'         => 15000,
                'duration_days' => 1,
                'description'   => 'Single day gym access.',
            ],
            [
                'name'          => '2 Days',
                'price'         => 25000,
                'duration_days' => 2,
                'description'   => 'Two consecutive days of gym access.',
            ],
            [
                'name'          => 'Weekly',
                'price'         => 60000,
                'duration_days' => 7,
                'description'   => 'Full week of unlimited gym access.',
            ],
           [
    'name'          => 'Monthly 3x',
    'price'         => 150000,
    'duration_days' => 30,
    'description'   => '3 days a week for a month.',
],
[
    'name'          => 'Monthly 4x',
    'price'         => 180000,
    'duration_days' => 30,
    'description'   => '4 days a week for a month.',
],
[
    'name'          => 'Monthly Unlimited',
    'price'         => 240000,
    'duration_days' => 30,
    'description'   => 'All days of the week for a month.',
],
        ];

        foreach ($memberships as $membership) {
            Membership::firstOrCreate(
                ['name' => $membership['name']],
                $membership
            );
        }
    }
}