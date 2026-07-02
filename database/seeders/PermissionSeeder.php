<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'dashboard.view',
            'admins.manage',
            'roles.manage',
            'permissions.manage',
            'cities.manage',
            'categories.manage',
            'restaurant-requests.review',
            'restaurants.manage',
            'menus.manage',
            'products.manage',
            'banners.manage',
            'orders.manage',
            'reviews.moderate',
            'settings.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'admin');
        }

        $rolePermissions = [
            'super-admin' => $permissions,
            'operations-manager' => [
                'dashboard.view',
                'cities.manage',
                'categories.manage',
                'restaurant-requests.review',
                'restaurants.manage',
                'orders.manage',
                'reviews.moderate',
            ],
            'content-manager' => [
                'dashboard.view',
                'restaurants.manage',
                'menus.manage',
                'products.manage',
                'banners.manage',
            ],
        ];

        foreach ($rolePermissions as $role => $rolePermissionNames) {
            Role::findOrCreate($role, 'admin')->syncPermissions($rolePermissionNames);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
