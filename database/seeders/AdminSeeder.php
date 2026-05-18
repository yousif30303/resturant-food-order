<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('admins')->updateOrInsert(
            ['email' => 'admin@restaurant.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'is_active' => true,
                'email_verified_at' => $now,
                'updated_at' => $now,
                'created_at' => $now,
            ]
        );

        $adminId = DB::table('admins')->where('email', 'admin@restaurant.com')->value('id');
        $roleId = DB::table('roles')->where('slug', 'super-admin')->value('id');

        if ($adminId && $roleId) {
            DB::table('admin_role')->upsert([
                [
                    'admin_id' => $adminId,
                    'role_id' => $roleId,
                ],
            ], ['admin_id', 'role_id'], []);
        }
    }
}
