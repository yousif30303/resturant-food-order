<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminActiveMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $admin = Auth::guard('admin')->user();

        if (! $admin) {
            return $next($request);
        }

        if (! $admin->is_active) {
            Auth::guard('admin')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return $this->inactiveRedirect();
        }

        return $next($request);
    }

    private function inactiveRedirect(): RedirectResponse
    {
        return redirect()
            ->route('admin.auth.login')
            ->withErrors([
                'email' => 'Your admin account is inactive. Please contact support.',
            ]);
    }
}
