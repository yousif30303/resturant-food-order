<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('banners')->upsert([
            [
                'title' => 'Discover the best food and drinks near you',
                'subtitle' => 'Browse top restaurants, fresh meals, and quick delivery options in one place.',
                'image_path' => 'website/assets/img/bg.png',
                'button_label' => 'Browse Restaurants',
                'button_url' => '/restaurants',
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Fresh meals from trusted local restaurants',
                'subtitle' => 'Find burgers, pizza, cafes, seafood, healthy bowls, and desserts.',
                'image_path' => 'website/assets/img/slider.png',
                'button_label' => 'Explore Popular Brands',
                'button_url' => '/restaurants',
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Order faster from restaurants you love',
                'subtitle' => 'A simple public browsing experience for restaurants, menus, and offers.',
                'image_path' => 'website/assets/img/slider1.png',
                'button_label' => 'View Gallery',
                'button_url' => '/gallery',
                'sort_order' => 3,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ], ['title'], [
            'subtitle',
            'image_path',
            'button_label',
            'button_url',
            'sort_order',
            'is_active',
            'updated_at',
        ]);
    }
}
