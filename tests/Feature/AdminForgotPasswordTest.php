<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\User;
use App\Notifications\AdminResetPasswordNotification;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class AdminForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $compiledViewPath = sys_get_temp_dir().DIRECTORY_SEPARATOR.'restaurant-food-order-test-views';

        if (! is_dir($compiledViewPath)) {
            mkdir($compiledViewPath, 0777, true);
        }

        config(['view.compiled' => $compiledViewPath]);
    }

    public function test_guest_admin_can_view_forgot_password_page(): void
    {
        $this->get(route('admin.auth.forgot-password'))
            ->assertOk()
            ->assertSee('Reset Password');
    }

    public function test_guest_admin_can_submit_forgot_password_request(): void
    {
        $this->createAdmin(['email' => 'admin@example.com']);

        $this->post(route('admin.auth.password.email'), [
            'email' => 'admin@example.com',
        ])->assertSessionHas('status');
    }

    public function test_admin_password_reset_notification_is_sent_when_email_exists(): void
    {
        Notification::fake();

        $admin = $this->createAdmin(['email' => 'admin@example.com']);

        $this->post(route('admin.auth.password.email'), [
            'email' => 'admin@example.com',
        ]);

        Notification::assertSentTo($admin, AdminResetPasswordNotification::class);
    }

    public function test_forgot_password_request_uses_admin_broker_not_public_user_broker(): void
    {
        Notification::fake();

        $admin = $this->createAdmin(['email' => 'shared@example.com']);
        $user = User::factory()->create(['email' => 'shared@example.com']);

        $this->post(route('admin.auth.password.email'), [
            'email' => 'shared@example.com',
        ])->assertSessionHas('status');

        Notification::assertSentTo($admin, AdminResetPasswordNotification::class);
        Notification::assertNotSentTo($user, ResetPassword::class);
    }

    public function test_guest_admin_can_view_reset_password_page_with_token(): void
    {
        $this->get(route('admin.auth.password.reset', [
            'token' => 'test-token',
            'email' => 'admin@example.com',
        ]))
            ->assertOk()
            ->assertSee('Create New Password');
    }

    public function test_reset_password_validation_requires_required_fields(): void
    {
        $this->post(route('admin.auth.password.update'), [])
            ->assertSessionHasErrors(['token', 'email', 'password']);

        $this->post(route('admin.auth.password.update'), [
            'token' => 'test-token',
            'email' => 'admin@example.com',
            'password' => 'new-secure-password',
        ])->assertSessionHasErrors('password');
    }

    public function test_valid_token_allows_admin_password_update_and_login_with_new_password(): void
    {
        $admin = $this->createAdmin([
            'email' => 'admin@example.com',
            'password' => 'old-password',
        ]);
        $token = Password::broker('admins')->createToken($admin);

        $this->post(route('admin.auth.password.update'), [
            'token' => $token,
            'email' => 'admin@example.com',
            'password' => 'new-secure-password',
            'password_confirmation' => 'new-secure-password',
        ])->assertRedirect(route('admin.auth.login'));

        $admin->refresh();

        $this->assertFalse(Hash::check('old-password', $admin->password));
        $this->assertTrue(Hash::check('new-secure-password', $admin->password));

        $this->post(route('admin.auth.attempt'), [
            'email' => 'admin@example.com',
            'password' => 'new-secure-password',
        ]);

        $this->assertAuthenticatedAs($admin, 'admin');
    }

    public function test_invalid_token_is_rejected(): void
    {
        $this->createAdmin(['email' => 'admin@example.com']);

        $this->post(route('admin.auth.password.update'), [
            'token' => 'invalid-token',
            'email' => 'admin@example.com',
            'password' => 'new-secure-password',
            'password_confirmation' => 'new-secure-password',
        ])->assertSessionHasErrors('email');
    }

    public function test_expired_token_is_rejected(): void
    {
        $admin = $this->createAdmin(['email' => 'admin@example.com']);
        $token = Password::broker('admins')->createToken($admin);

        DB::table('password_reset_tokens')
            ->where('email', 'admin@example.com')
            ->update([
                'created_at' => now()->subMinutes(config('auth.passwords.admins.expire') + 1),
            ]);

        $this->post(route('admin.auth.password.update'), [
            'token' => $token,
            'email' => 'admin@example.com',
            'password' => 'new-secure-password',
            'password_confirmation' => 'new-secure-password',
        ])->assertSessionHasErrors('email');
    }

    public function test_public_user_password_reset_broker_remains_unaffected(): void
    {
        $user = User::factory()->create(['email' => 'customer@example.com']);
        $token = Password::broker('users')->createToken($user);

        $this->assertTrue(Password::broker('users')->tokenExists($user, $token));
        $this->assertSame('users', config('auth.passwords.users.provider'));
        $this->assertSame('password_reset_tokens', config('auth.passwords.users.table'));
    }

    public function test_public_users_cannot_reset_password_through_admin_broker(): void
    {
        Notification::fake();

        $user = User::factory()->create(['email' => 'customer@example.com']);

        $this->post(route('admin.auth.password.email'), [
            'email' => 'customer@example.com',
        ])->assertSessionHas('status');

        Notification::assertNotSentTo($user, ResetPassword::class);
        $this->assertDatabaseMissing('password_reset_tokens', [
            'email' => 'customer@example.com',
        ]);
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
