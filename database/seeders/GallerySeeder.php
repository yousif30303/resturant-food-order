<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $restaurants = DB::table('restaurants')
            ->where('status', 'approved')
            ->orderBy('id')
            ->pluck('id', 'slug');

        $galleryItems = [
            ['restaurant' => 'desert-grill-house', 'title' => 'Signature Grill', 'image' => 'website/assets/img/gallery/1.png'],
            ['restaurant' => 'stone-oven-pizza', 'title' => 'Fresh From the Oven', 'image' => 'website/assets/img/gallery/2.png'],
            ['restaurant' => 'palm-brew-cafe', 'title' => 'Cafe Favorites', 'image' => 'website/assets/img/gallery/3.png'],
            ['restaurant' => 'green-bowl-kitchen', 'title' => 'Fresh Healthy Bowls', 'image' => 'website/assets/img/list/1.png'],
            ['restaurant' => 'harbor-catch-seafood', 'title' => 'Coastal Seafood Plate', 'image' => 'website/assets/img/list/2.png'],
            ['restaurant' => 'golden-crust-bakery', 'title' => 'Daily Baked Selection', 'image' => 'website/assets/img/list/3.png'],
            ['restaurant' => 'metro-bean-cafe', 'title' => 'Coffee and Brunch', 'image' => 'website/assets/img/list/4.png'],
            ['restaurant' => 'fresh-press-kitchen', 'title' => 'Fresh Pressed Favorites', 'image' => 'website/assets/img/list/5.png'],
            ['restaurant' => 'stack-street-burgers', 'title' => 'Stacked Burger Meal', 'image' => 'website/assets/img/list/6.png'],
            ['restaurant' => 'mozza-corner', 'title' => 'Mozzarella Pizza', 'image' => 'website/assets/img/list/7.png'],
            ['restaurant' => 'sweet-layer-patisserie', 'title' => 'Patisserie Selection', 'image' => 'website/assets/img/list/8.png'],
            ['restaurant' => 'blue-pearl-fish-house', 'title' => 'Fresh Fish Special', 'image' => 'website/assets/img/list/9.png'],
        ];

        foreach ($galleryItems as $index => $galleryItem) {
            $restaurantId = $restaurants[$galleryItem['restaurant']] ?? null;

            if (! $restaurantId) {
                continue;
            }

            DB::table('galleries')->updateOrInsert(
                [
                    'restaurant_id' => $restaurantId,
                    'image_path' => $galleryItem['image'],
                ],
                [
                    'title' => $galleryItem['title'],
                    'sort_order' => $index + 1,
                    'is_active' => true,
                    'deleted_at' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}
