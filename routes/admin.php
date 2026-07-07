<?php

use App\Http\Controllers\Admin\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [LoginController::class, 'create'])->name('auth.login');
        Route::post('/login', [LoginController::class, 'store'])->middleware('throttle:admin-login')->name('auth.attempt');
        Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])->name('auth.forgot-password');
        Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])->middleware('throttle:forgot-password')->name('auth.password.email');
        Route::get('/reset-password/{token}', [ResetPasswordController::class, 'create'])->name('auth.password.reset');
        Route::post('/reset-password', [ResetPasswordController::class, 'store'])->name('auth.password.update');
    });

    Route::middleware(['auth:admin', 'active.admin'])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [LoginController::class, 'destroy'])->name('auth.logout');
    });
});
