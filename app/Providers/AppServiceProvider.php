<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('admin-login', function (Request $request) {
            return Limit::perMinute(5)->by($this->throttleKey($request));
        });

        RateLimiter::for('client-login', function (Request $request) {
            return Limit::perMinute(5)->by($this->throttleKey($request));
        });

        RateLimiter::for('user-login', function (Request $request) {
            return Limit::perMinute(10)->by($this->throttleKey($request));
        });

        RateLimiter::for('forgot-password', function (Request $request) {
            return Limit::perMinute(3)->by($this->throttleKey($request));
        });
    }

    private function throttleKey(Request $request): string
    {
        $identifier = (string) (
            $request->input('email')
            ?: $request->input('username')
            ?: $request->ip()
        );

        return mb_strtolower($identifier).'|'.$request->ip();
    }
}
