<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('settings')->upsert([
            [
                'key' => 'site_name',
                'value' => 'Restaurant Platform',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'support_email',
                'value' => 'support@restaurant.com',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'support_phone',
                'value' => '+971500000000',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'default_currency',
                'value' => 'AED',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'platform_status',
                'value' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ], ['key'], ['value', 'updated_at']);
    }
}
