<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $quickSearchCategories = collect([
            ['name' => 'American', 'count' => 156, 'image' => 'website/assets/img/list/1.png'],
            ['name' => 'Pizza', 'count' => 120, 'image' => 'website/assets/img/list/2.png'],
            ['name' => 'Healthy', 'count' => 130, 'image' => 'website/assets/img/list/3.png'],
            ['name' => 'Vegetarian', 'count' => 120, 'image' => 'website/assets/img/list/4.png'],
            ['name' => 'Chinese', 'count' => 111, 'image' => 'website/assets/img/list/5.png'],
            ['name' => 'Hamburgers', 'count' => 958, 'image' => 'website/assets/img/list/6.png'],
            ['name' => 'Dessert', 'count' => 56, 'image' => 'website/assets/img/list/7.png'],
            ['name' => 'Chicken', 'count' => 40, 'image' => 'website/assets/img/list/8.png'],
            ['name' => 'Indian', 'count' => 156, 'image' => 'website/assets/img/list/9.png'],
        ]);

        $homepagePromotions = collect([
            ['image' => 'website/assets/img/pro1.jpg', 'alt' => 'Featured food promotion'],
            ['image' => 'website/assets/img/pro2.jpg', 'alt' => 'Restaurant offer promotion'],
            ['image' => 'website/assets/img/pro3.jpg', 'alt' => 'Popular meals promotion'],
            ['image' => 'website/assets/img/pro4.jpg', 'alt' => 'Food delivery promotion'],
        ]);

        $popularRestaurants = Restaurant::query()
            ->with(['category', 'city'])
            ->where('status', 'approved')
            ->latest()
            ->limit(8)
            ->get();

        return view('website.home.index', compact('popularRestaurants', 'quickSearchCategories', 'homepagePromotions'));
    }
}
