<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\City;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RestaurantController extends Controller
{
    public function index(Request $request): View
    {
        $filters = [
            'city' => is_string($request->query('city')) ? $request->query('city') : null,
            'category' => is_string($request->query('category')) ? $request->query('category') : null,
        ];

        $cities = City::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);

        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);

        $restaurants = Restaurant::query()
            ->with(['category', 'city'])
            ->where('status', 'approved')
            ->whereHas('city', fn ($query) => $query->where('is_active', true))
            ->whereHas('category', fn ($query) => $query->where('is_active', true))
            ->when($filters['city'], function ($query, string $citySlug) {
                $query->whereHas('city', fn ($cityQuery) => $cityQuery->where('slug', $citySlug));
            })
            ->when($filters['category'], function ($query, string $categorySlug) {
                $query->whereHas('category', fn ($categoryQuery) => $categoryQuery->where('slug', $categorySlug));
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('website.restaurants.index', compact('restaurants', 'cities', 'categories', 'filters'));
    }

    public function show(Restaurant $restaurant): View
    {
        return view('website.restaurants.show', compact('restaurant'));
    }
}
