<?php

use App\Http\Controllers\Website\GalleryController;
use App\Http\Controllers\Website\HomeController;
use App\Http\Controllers\Website\RestaurantController;
use Illuminate\Support\Facades\Route;

Route::name('website.')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::prefix('restaurants')->name('restaurants.')->controller(RestaurantController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{restaurant:slug}', 'show')->name('show');
    });

    Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
});
