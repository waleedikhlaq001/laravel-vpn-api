<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $plan = [
            'name' => 'Plan 1',
            'description' => 'plan1',
            'price' => 59,
            'duration' => 365,
        ];

        \App\Models\Plan::create($plan);
    }
}
