<?php

use App\Http\Controllers\Client\DashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('client')->name('client.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
});
