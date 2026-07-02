<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route as RouteFacade;
use Tests\TestCase;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_admin_can_view_login_page(): void
    {
        $this->get(route('admin.auth.login'))
            ->assertOk()
            ->assertSee('Welcome Back');
    }

    public function test_authenticated_admin_is_redirected_away_from_login_page(): void
    {
        $admin = $this->createAdmin();

        $this->actingAs($admin, 'admin')
            ->get(route('admin.auth.login'))
            ->assertRedirect(route('admin.dashboard'));
    }

    public function test_admin_login_validation_requires_email_and_password(): void
    {
        $this->post(route('admin.auth.attempt'), [])
            ->assertSessionHasErrors(['email', 'password']);
    }

    public function test_invalid_admin_credentials_fail_with_errors(): void
    {
        $this->createAdmin();

        $this->post(route('admin.auth.attempt'), [
            'email' => 'admin@example.com',
            'password' => 'wrong-password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest('admin');
    }

    public function test_valid_admin_credentials_authenticate_using_admin_guard(): void
    {
        $admin = $this->createAdmin();

        $this->post(route('admin.auth.attempt'), [
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($admin, 'admin');
        $this->assertFalse(Auth::guard('web')->check());
    }

    public function test_successful_admin_login_redirects_to_dashboard(): void
    {
        $this->createAdmin();

        $this->post(route('admin.auth.attempt'), [
            'email' => 'admin@example.com',
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'));
    }

    public function test_successful_admin_login_regenerates_session(): void
    {
        $this->createAdmin();

        $this->withSession(['login_marker' => 'before-login']);
        $previousSessionId = session()->getId();

        $this->post(route('admin.auth.attempt'), [
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $this->assertNotSame($previousSessionId, session()->getId());
    }

    public function test_admin_logout_logs_out_admin_guard_and_redirects_to_login(): void
    {
        $admin = $this->createAdmin();

        $this->actingAs($admin, 'admin')
            ->post(route('admin.auth.logout'))
            ->assertRedirect(route('admin.auth.login'));

        $this->assertGuest('admin');
    }

    public function test_admin_logout_invalidates_session_and_regenerates_csrf_token(): void
    {
        $admin = $this->createAdmin();

        $this->withSession([
            '_token' => 'old-token',
            'admin_session_marker' => 'remove-me',
        ]);

        $this->actingAs($admin, 'admin')
            ->post(route('admin.auth.logout'));

        $this->assertFalse(session()->has('admin_session_marker'));
        $this->assertNotSame('old-token', session()->token());
    }

    public function test_admin_dashboard_requires_admin_authentication(): void
    {
        $route = RouteFacade::getRoutes()->getByName('admin.dashboard');

        $this->assertNotNull($route);
        $this->assertContains('auth:admin', $route->gatherMiddleware());

        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('admin.auth.login'));
    }

    public function test_public_web_guard_user_authentication_remains_unaffected(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'web');

        $this->assertAuthenticatedAs($user, 'web');
        $this->assertGuest('admin');
    }

    public function test_admin_login_does_not_authenticate_public_users_through_admin_guard(): void
    {
        User::factory()->create([
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
        ]);

        $this->post(route('admin.auth.attempt'), [
            'email' => 'customer@example.com',
            'password' => 'password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest('admin');
        $this->assertGuest('web');
    }

    private function createAdmin(array $attributes = []): Admin
    {
        return Admin::create([
            'name' => $attributes['name'] ?? 'Admin User',
            'email' => $attributes['email'] ?? 'admin@example.com',
            'password' => Hash::make($attributes['password'] ?? 'password'),
            'is_active' => $attributes['is_active'] ?? true,
        ]);
    }
}
