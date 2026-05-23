<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RestaurantSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $clients = DB::table('clients')->pluck('id', 'email');
        $cities = DB::table('cities')->pluck('id', 'slug');
        $categories = DB::table('categories')->pluck('id', 'slug');
        $approvedBy = DB::table('admins')->where('email', 'admin@restaurant.com')->value('id');

        $restaurants = [
            [
                'client_email' => 'client1@restaurant.com',
                'city_slug' => 'dubai',
                'category_slug' => 'burger',
                'name' => 'Desert Grill House',
                'description' => 'Premium burgers and grilled meals.',
                'phone' => '+971400000101',
                'email' => 'contact@desertgrillhouse.com',
                'address' => 'Jumeirah, Dubai',
            ],
            [
                'client_email' => 'client1@restaurant.com',
                'city_slug' => 'abu-dhabi',
                'category_slug' => 'pizza',
                'name' => 'Stone Oven Pizza',
                'description' => 'Wood-fired pizza with classic Italian flavors.',
                'phone' => '+971400000102',
                'email' => 'hello@stoneovenpizza.com',
                'address' => 'Corniche, Abu Dhabi',
            ],
            [
                'client_email' => 'client2@restaurant.com',
                'city_slug' => 'sharjah',
                'category_slug' => 'cafe',
                'name' => 'Palm Brew Cafe',
                'description' => 'Specialty coffee, breakfast, and desserts.',
                'phone' => '+971400000103',
                'email' => 'team@palmbrewcafe.com',
                'address' => 'Al Majaz, Sharjah',
            ],
            [
                'client_email' => 'client3@restaurant.com',
                'city_slug' => 'dubai',
                'category_slug' => 'healthy',
                'name' => 'Green Bowl Kitchen',
                'description' => 'Healthy bowls, salads, and fresh juices.',
                'phone' => '+971400000104',
                'email' => 'care@greenbowlkitchen.com',
                'address' => 'Dubai Marina, Dubai',
            ],
            [
                'client_email' => 'client2@restaurant.com',
                'city_slug' => 'ajman',
                'category_slug' => 'seafood',
                'name' => 'Harbor Catch Seafood',
                'description' => 'Fresh grilled seafood, rice platters, and coastal specials.',
                'phone' => '+971400000105',
                'email' => 'orders@harborcatchseafood.com',
                'address' => 'Ajman Corniche, Ajman',
            ],
            [
                'client_email' => 'client3@restaurant.com',
                'city_slug' => 'al-ain',
                'category_slug' => 'bakery',
                'name' => 'Golden Crust Bakery',
                'description' => 'Daily baked bread, pastries, cakes, and warm desserts.',
                'phone' => '+971400000106',
                'email' => 'hello@goldencrustbakery.com',
                'address' => 'Al Jimi, Al Ain',
            ],
            [
                'client_email' => 'client1@restaurant.com',
                'city_slug' => 'dubai',
                'category_slug' => 'cafe',
                'name' => 'Metro Bean Cafe',
                'description' => 'Coffee, sandwiches, brunch plates, and quick bites.',
                'phone' => '+971400000107',
                'email' => 'team@metrobeancafe.com',
                'address' => 'Business Bay, Dubai',
            ],
            [
                'client_email' => 'client2@restaurant.com',
                'city_slug' => 'abu-dhabi',
                'category_slug' => 'healthy',
                'name' => 'Fresh Press Kitchen',
                'description' => 'Cold-pressed juices, wraps, bowls, and balanced meals.',
                'phone' => '+971400000108',
                'email' => 'care@freshpresskitchen.com',
                'address' => 'Al Reem Island, Abu Dhabi',
            ],
            [
                'client_email' => 'client3@restaurant.com',
                'city_slug' => 'sharjah',
                'category_slug' => 'burger',
                'name' => 'Stack Street Burgers',
                'description' => 'Stacked burgers, crispy fries, loaded sides, and shakes.',
                'phone' => '+971400000109',
                'email' => 'hello@stackstreetburgers.com',
                'address' => 'Muwailih, Sharjah',
            ],
            [
                'client_email' => 'client1@restaurant.com',
                'city_slug' => 'ajman',
                'category_slug' => 'pizza',
                'name' => 'Mozza Corner',
                'description' => 'Cheesy pizzas, baked pasta, garlic bread, and family combos.',
                'phone' => '+971400000110',
                'email' => 'orders@mozzacorner.com',
                'address' => 'Al Nuaimiya, Ajman',
            ],
            [
                'client_email' => 'client2@restaurant.com',
                'city_slug' => 'dubai',
                'category_slug' => 'bakery',
                'name' => 'Sweet Layer Patisserie',
                'description' => 'Cakes, tarts, cookies, and celebration dessert boxes.',
                'phone' => '+971400000111',
                'email' => 'orders@sweetlayerpatisserie.com',
                'address' => 'Mirdif, Dubai',
            ],
            [
                'client_email' => 'client3@restaurant.com',
                'city_slug' => 'abu-dhabi',
                'category_slug' => 'seafood',
                'name' => 'Blue Pearl Fish House',
                'description' => 'Seafood grills, fried fish meals, soups, and salads.',
                'phone' => '+971400000112',
                'email' => 'hello@bluepearlfishhouse.com',
                'address' => 'Khalifa City, Abu Dhabi',
            ],
        ];

        $rows = [];

        foreach ($restaurants as $restaurant) {
            $clientId = $clients[$restaurant['client_email']] ?? null;
            $cityId = $cities[$restaurant['city_slug']] ?? null;
            $categoryId = $categories[$restaurant['category_slug']] ?? null;

            if (! $clientId || ! $cityId || ! $categoryId) {
                continue;
            }

            $rows[] = [
                'client_id' => $clientId,
                'city_id' => $cityId,
                'category_id' => $categoryId,
                'restaurant_request_id' => null,
                'name' => $restaurant['name'],
                'slug' => Str::slug($restaurant['name']),
                'description' => $restaurant['description'],
                'phone' => $restaurant['phone'],
                'email' => $restaurant['email'],
                'address' => $restaurant['address'],
                'status' => 'approved',
                'approved_by' => $approvedBy,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if ($rows !== []) {
            DB::table('restaurants')->upsert(
                $rows,
                ['slug'],
                [
                    'client_id',
                    'city_id',
                    'category_id',
                    'description',
                    'phone',
                    'email',
                    'address',
                    'status',
                    'approved_by',
                    'updated_at',
                ]
            );
        }
    }
}
