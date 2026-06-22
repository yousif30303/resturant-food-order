<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(): View
    {
        $galleryItems = Gallery::query()
            ->active()
            ->whereHas('restaurant', function ($query) {
                $query->where('status', 'approved')
                    ->whereHas('city', fn ($cityQuery) => $cityQuery->where('is_active', true))
                    ->whereHas('category', fn ($categoryQuery) => $categoryQuery->where('is_active', true));
            })
            ->with('restaurant:id,name,slug')
            ->ordered()
            ->paginate(12);

        return view('website.gallery.index', compact('galleryItems'));
    }
}
