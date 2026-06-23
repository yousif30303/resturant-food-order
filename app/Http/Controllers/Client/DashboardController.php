<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $clientId = Auth::guard('client')->id();

        $restaurantQuery = Restaurant::query()
            ->when($clientId, fn ($query) => $query->where('client_id', $clientId));

        $galleryQuery = Gallery::query()
            ->when($clientId, function ($query) use ($clientId) {
                $query->whereHas('restaurant', fn ($restaurantQuery) => $restaurantQuery->where('client_id', $clientId));
            });

        $stats = [
            'restaurants' => (clone $restaurantQuery)->count(),
            'approved_restaurants' => (clone $restaurantQuery)->where('status', 'approved')->count(),
            'pending_restaurants' => (clone $restaurantQuery)->where('status', 'pending')->count(),
            'gallery_images' => (clone $galleryQuery)->count(),
        ];

        $recentRestaurants = (clone $restaurantQuery)
            ->with(['category:id,name', 'city:id,name'])
            ->latest()
            ->limit(5)
            ->get();

        return view('client.dashboard.index', compact('stats', 'recentRestaurants'));
    }
}
