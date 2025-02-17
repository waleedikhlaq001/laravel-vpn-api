<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groups = [
            [
                'name' => 'G1-Private',
                'description' => 'a private group',
                'status' => 'active',
                'type' => 'private', // 'private', 'public'
                'user_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'G2-Private',
                'description' => 'a private group',
                'status' => 'active',
                'type' => 'private', // 'private', 'public'
                'user_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'G2-Public',
                'description' => 'a public group',
                'status' => 'active',
                'type' => 'public', // 'private', 'public'
                'user_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($groups as $group) {
            \App\Models\Group::create($group);
        }
    }
}
