<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\View\View;

class RestaurantController extends Controller
{
    public function index(): View
    {
        return view('website.restaurants.index');
    }

    public function show(Restaurant $restaurant): View
    {
        return view('website.restaurants.show', compact('restaurant'));
    }
}
