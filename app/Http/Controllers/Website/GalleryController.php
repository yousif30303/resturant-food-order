<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(): View
    {
        return view('website.gallery.index');
    }
}
