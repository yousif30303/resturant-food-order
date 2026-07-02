<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $role = Role::findOrCreate('super-admin', 'admin');
        $email = env('ADMIN_DEFAULT_EMAIL', 'admin@example.com');
        $password = env('ADMIN_DEFAULT_PASSWORD');

        if (! $password && app()->isProduction()) {
            throw new RuntimeException('ADMIN_DEFAULT_PASSWORD must be set before seeding the default admin in production.');
        }

        $admin = Admin::firstOrNew(['email' => $email]);

        if (! $admin->exists) {
            $admin->password = Hash::make($password ?? 'password');
        }

        $admin->fill([
            'name' => env('ADMIN_DEFAULT_NAME', 'Super Admin'),
            'email' => $email,
            'is_active' => true,
        ]);

        if (! $admin->email_verified_at) {
            $admin->email_verified_at = now();
        }

        $admin->save();
        $admin->assignRole($role);
    }
}
