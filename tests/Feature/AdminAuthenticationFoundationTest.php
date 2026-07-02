<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\User;
use Database\Seeders\AdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route as RouteFacade;
use Spatie\Permission\Exceptions\GuardDoesNotMatch;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Tests\TestCase;

class AdminAuthenticationFoundationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_model_exists_and_uses_admin_guard_for_spatie_roles(): void
    {
        $this->assertTrue(class_exists(Admin::class));
        $this->assertContains(HasRoles::class, class_uses_recursive(Admin::class));

        $admin = Admin::create([
            'name' => 'Foundation Admin',
            'email' => 'foundation-admin@example.com',
            'password' => 'password',
            'is_active' => true,
        ]);

        $adminRole = Role::findOrCreate('foundation-role', 'admin');

        $admin->assignRole($adminRole);

        $this->assertTrue($admin->hasRole('foundation-role', 'admin'));
        $this->assertDatabaseHas('model_has_roles', [
            'role_id' => $adminRole->id,
            'model_type' => Admin::class,
            'model_id' => $admin->id,
        ]);
    }

    public function test_admin_can_be_created_in_admins_table(): void
    {
        $admin = Admin::create([
            'name' => 'Table Admin',
            'email' => 'table-admin@example.com',
            'password' => 'password',
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('admins', [
            'id' => $admin->id,
            'name' => 'Table Admin',
            'email' => 'table-admin@example.com',
            'is_active' => true,
        ]);
    }

    public function test_super_admin_seeder_creates_admin_role_and_assignment(): void
    {
        $this->seed(AdminSeeder::class);

        $admin = Admin::where('email', 'admin@example.com')->firstOrFail();
        $role = Role::where('name', 'super-admin')->where('guard_name', 'admin')->firstOrFail();

        $this->assertTrue($admin->hasRole($role));
        $this->assertDatabaseHas('roles', [
            'name' => 'super-admin',
            'guard_name' => 'admin',
        ]);
        $this->assertDatabaseHas('admins', [
            'id' => $admin->id,
            'email' => 'admin@example.com',
            'is_active' => true,
        ]);
    }

    public function test_super_admin_seeder_is_idempotent(): void
    {
        $this->seed(AdminSeeder::class);
        $this->seed(AdminSeeder::class);

        $admin = Admin::where('email', 'admin@example.com')->firstOrFail();
        $role = Role::where('name', 'super-admin')->where('guard_name', 'admin')->firstOrFail();

        $this->assertSame(1, Admin::where('email', 'admin@example.com')->count());
        $this->assertSame(1, Role::where('name', 'super-admin')->where('guard_name', 'admin')->count());
        $this->assertSame(1, $admin->roles()->whereKey($role->id)->count());
    }

    public function test_admin_dashboard_route_requires_admin_authentication(): void
    {
        $route = RouteFacade::getRoutes()->getByName('admin.dashboard');

        $this->assertNotNull($route);
        $this->assertContains('auth:admin', $route->gatherMiddleware());
    }

    public function test_admin_login_placeholder_route_is_accessible_without_login(): void
    {
        $this->get(route('admin.auth.login'))
            ->assertOk()
            ->assertSee('Admin login placeholder');
    }

    public function test_public_web_guard_user_authentication_is_not_affected(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'web');

        $this->assertTrue(Auth::guard('web')->check());
        $this->assertFalse(Auth::guard('admin')->check());
        $this->assertSame(User::class, config('auth.providers.users.model'));
        $this->assertSame('users', config('auth.guards.web.provider'));
    }

    public function test_spatie_admin_roles_do_not_use_web_guard(): void
    {
        $adminRole = Role::findOrCreate('super-admin', 'admin');

        $this->assertSame('admin', $adminRole->guard_name);
        $this->assertFalse(Role::where('name', 'super-admin')->where('guard_name', 'web')->exists());

        $admin = Admin::create([
            'name' => 'Guarded Admin',
            'email' => 'guarded-admin@example.com',
            'password' => 'password',
            'is_active' => true,
        ]);

        $webRole = Role::findOrCreate('web-only-role', 'web');

        $this->expectException(GuardDoesNotMatch::class);

        $admin->assignRole($webRole);
    }
}
