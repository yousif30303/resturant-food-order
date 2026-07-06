<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route as RouteFacade;
use Tests\TestCase;

class AdminActiveMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_admin_login_route_remains_accessible(): void
    {
        $this->get(route('admin.auth.login'))
            ->assertOk()
            ->assertSee('Welcome Back');
    }

    public function test_protected_admin_dashboard_route_requires_admin_authentication(): void
    {
        $route = RouteFacade::getRoutes()->getByName('admin.dashboard');

        $this->assertNotNull($route);
        $this->assertContains('auth:admin', $route->gatherMiddleware());
        $this->assertContains('active.admin', $route->gatherMiddleware());

        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('admin.auth.login'));
    }

    public function test_active_admin_can_access_protected_admin_dashboard_route(): void
    {
        $admin = $this->createAdmin(['is_active' => true]);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.dashboard'))
            ->assertOk();
    }

    public function test_inactive_admin_cannot_access_protected_admin_dashboard_route(): void
    {
        $admin = $this->createAdmin(['is_active' => false]);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.dashboard'))
            ->assertRedirect(route('admin.auth.login'));
    }

    public function test_inactive_admin_is_logged_out_from_admin_guard(): void
    {
        $admin = $this->createAdmin(['is_active' => false]);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.dashboard'));

        $this->assertGuest('admin');
    }

    public function test_inactive_admin_receives_error_message(): void
    {
        $admin = $this->createAdmin(['is_active' => false]);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.dashboard'))
            ->assertSessionHasErrors([
                'email' => 'Your admin account is inactive. Please contact support.',
            ]);
    }

    public function test_public_web_guard_user_authentication_remains_unaffected(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'web')
            ->get(route('admin.dashboard'))
            ->assertRedirect(route('admin.auth.login'));

        $this->assertAuthenticatedAs($user, 'web');
        $this->assertGuest('admin');
    }

    public function test_active_middleware_is_not_applied_to_guest_admin_routes(): void
    {
        $loginRoute = RouteFacade::getRoutes()->getByName('admin.auth.login');
        $attemptRoute = RouteFacade::getRoutes()->getByName('admin.auth.attempt');

        $this->assertNotNull($loginRoute);
        $this->assertNotNull($attemptRoute);
        $this->assertNotContains('active.admin', $loginRoute->gatherMiddleware());
        $this->assertNotContains('active.admin', $attemptRoute->gatherMiddleware());
    }

    public function test_active_middleware_does_not_require_roles_or_permissions(): void
    {
        $admin = $this->createAdmin(['is_active' => true]);

        $this->assertTrue($admin->roles()->doesntExist());

        $this->actingAs($admin, 'admin')
            ->get(route('admin.dashboard'))
            ->assertOk();
    }

    private function createAdmin(array $attributes = []): Admin
    {
        return Admin::create([
            'name' => $attributes['name'] ?? 'Admin User',
            'email' => $attributes['email'] ?? fake()->unique()->safeEmail(),
            'password' => Hash::make($attributes['password'] ?? 'password'),
            'is_active' => $attributes['is_active'] ?? true,
        ]);
    }
}
