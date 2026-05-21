<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $popularRestaurants = Restaurant::query()
            ->with(['category', 'city'])
            ->where('status', 'approved')
            ->latest()
            ->limit(8)
            ->get();

        return view('website.home.index', compact('popularRestaurants'));
    }
}
