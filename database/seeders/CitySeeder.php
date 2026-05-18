<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $cities = ['Dubai', 'Abu Dhabi', 'Sharjah', 'Ajman', 'Al Ain'];

        DB::table('cities')->upsert(
            array_map(fn (string $city) => [
                'name' => $city,
                'slug' => Str::slug($city),
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ], $cities),
            ['slug'],
            ['name', 'is_active', 'updated_at']
        );
    }
}
