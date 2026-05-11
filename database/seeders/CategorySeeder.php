<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $categories = ['Burger', 'Pizza', 'Cafe', 'Bakery', 'Seafood', 'Healthy'];

        DB::table('categories')->upsert(
            array_map(fn (string $category) => [
                'name' => $category,
                'slug' => Str::slug($category),
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ], $categories),
            ['slug'],
            ['name', 'is_active', 'updated_at']
        );
    }
}
