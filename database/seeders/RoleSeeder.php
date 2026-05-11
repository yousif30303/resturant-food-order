<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('roles')->upsert([
            [
                'name' => 'Super Admin',
                'slug' => 'super-admin',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Operations Manager',
                'slug' => 'operations-manager',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Content Manager',
                'slug' => 'content-manager',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ], ['slug'], ['name', 'updated_at']);
    }
}
