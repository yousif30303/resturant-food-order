<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('clients')->upsert([
            [
                'name' => 'Ahmed Al Mansoori',
                'email' => 'client1@restaurant.com',
                'phone' => '+971500000101',
                'password' => Hash::make('password'),
                'is_active' => true,
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Sara Al Nuaimi',
                'email' => 'client2@restaurant.com',
                'phone' => '+971500000102',
                'password' => Hash::make('password'),
                'is_active' => true,
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Omar Khalid',
                'email' => 'client3@restaurant.com',
                'phone' => '+971500000103',
                'password' => Hash::make('password'),
                'is_active' => true,
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ], ['email'], ['name', 'phone', 'password', 'is_active', 'email_verified_at', 'updated_at']);
    }
}
