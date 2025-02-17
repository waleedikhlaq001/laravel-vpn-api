<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'fname' => 'admin',
                'lname' => 'admin',
                'email' => 'admin@ekarto.com',
                'tel' => '0765001010',
                'status' => 'active', // 'active', 'inactive', 'pending', 'blocked
                'email_verified_at' => now(),
                'password' => bcrypt('admin12345'),
                'profession' => 'admin',
                'payement_plan_id' => 1,
                'payement_method' => 'self',
                'subscription' => 'paidbygroup',
                'payement_status' => 'payed',
                'payement_group_id' => null,
                'payement_end_at' => null,
                'role' => 'admin',
                'lang' => 'fr',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'fname' => 'leader',
                'lname' => 'simple',
                'email_verified_at' => now(),
                'status' => 'active', // 'active', 'inactive', 'pending', 'blocked
                'email' => 'leader@ekarto.com',
                'tel' => '0765001010',
                'password' => bcrypt('leader12345'),
                'profession' => 'member',
                'payement_plan_id' => 1,
                'payement_method' => 'self',
                'subscription' => 'paidbygroup',
                'payement_status' => 'payed',
                'payement_group_id' => null,
                'payement_end_at' => null,
                'role' => 'member',
                'lang' => 'fr',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            [
                'fname' => 'member',
                'lname' => 'simple',
                'email_verified_at' => now(),
                'status' => 'active', // 'active', 'inactive', 'pending', 'blocked
                'email' => 'member@ekarto.com',
                'tel' => '0765001010',
                'password' => bcrypt('member12345'),
                'profession' => 'member',
                'payement_plan_id' => 1,
                'payement_method' => 'self',
                'subscription' => 'paidbygroup',
                'payement_status' => 'payed',
                'payement_group_id' => null,
                'payement_end_at' => null,
                'role' => 'member',
                'lang' => 'fr',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            [
                'fname' => 'member',
                'lname' => 'simple',
                'email_verified_at' => now(),
                'status' => 'active', // 'active', 'inactive', 'pending', 'blocked
                'email' => 'member2@ekarto.com',
                'tel' => '0765001010',
                'password' => bcrypt('member12345'),
                'profession' => 'member',
                'payement_plan_id' => 1,
                'subscription' => 'paidbygroup',
                'payement_method' => 'self',
                'payement_status' => 'payed',
                'payement_group_id' => null,
                'payement_end_at' => null,
                'role' => 'member',
                'lang' => 'fr',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        $groups = [
            [
                'name' => 'G1-Private',
                'description' => 'a private group',
                'status' => 'active',
                'type' => 'private', // 'private', 'public'
                'user_id' => 1,
                'permissions' => 'read',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'G2-Private',
                'description' => 'a private group',
                'status' => 'active',
                'type' => 'private', // 'private', 'public'
                'user_id' => 1,
                'permissions' => 'readwrite',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'G3-Public',
                'description' => 'a public group',
                'status' => 'active',
                'type' => 'public', // 'private', 'public'
                'user_id' => 1,
                'permissions' => 'read',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'G4-Public',
                'description' => 'a public group',
                'status' => 'active',
                'type' => 'public', // 'private', 'public'
                'user_id' => 1,
                'permissions' => 'readwrite',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($users as $user) {
            $u = \App\Models\User::create($user);
           
            /** user1 and user2 are both admins not should be affetcetd to any group
             * member1 should be affected to group1 as admin
             * member2 should be affected to group1 as member
             * member3 should be affected to group2 as admin
             * member4 should be affected to group2 as member
             * member5 should be affected to group1 as member
             */
            if ($u->id == 1) {
                continue;
            }
            if($u->id == 2) {
                foreach ($groups as $group) {
                 $g = \App\Models\Group::create($group);
                }
                $u->groups()->attach(3, ['role' => 'leader', 'status' => 'active', 'group_permission' => 'allrights']);
                continue;
            }
            if ($u->id == 3) {
                $u->groups()->attach(3, ['role' => 'member', 'status' => 'active', 'group_permission' => 'allrights']);
                continue;
            }
            if ($u->id == 4) {
                $u->groups()->attach(1, ['role' => 'member', 'status' => 'active', 'group_permission' => 'allrights']);
                continue;
            }
        }
    }
}
