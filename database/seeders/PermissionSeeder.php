<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $permissions = [
            ['name' => 'View Dashboard', 'slug' => 'dashboard.view'],
            ['name' => 'Manage Admins', 'slug' => 'admins.manage'],
            ['name' => 'Manage Roles', 'slug' => 'roles.manage'],
            ['name' => 'Manage Permissions', 'slug' => 'permissions.manage'],
            ['name' => 'Manage Cities', 'slug' => 'cities.manage'],
            ['name' => 'Manage Categories', 'slug' => 'categories.manage'],
            ['name' => 'Review Restaurant Requests', 'slug' => 'restaurant-requests.review'],
            ['name' => 'Manage Restaurants', 'slug' => 'restaurants.manage'],
            ['name' => 'Manage Menus', 'slug' => 'menus.manage'],
            ['name' => 'Manage Products', 'slug' => 'products.manage'],
            ['name' => 'Manage Banners', 'slug' => 'banners.manage'],
            ['name' => 'Manage Orders', 'slug' => 'orders.manage'],
            ['name' => 'Moderate Reviews', 'slug' => 'reviews.moderate'],
            ['name' => 'Manage Settings', 'slug' => 'settings.manage'],
        ];

        DB::table('permissions')->upsert(
            array_map(fn (array $permission) => [
                ...$permission,
                'created_at' => $now,
                'updated_at' => $now,
            ], $permissions),
            ['slug'],
            ['name', 'updated_at']
        );

        $roles = DB::table('roles')->pluck('id', 'slug');
        $permissionIds = DB::table('permissions')->pluck('id', 'slug');

        $rolePermissions = [
            'super-admin' => array_keys($permissionIds->all()),
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

        foreach ($rolePermissions as $roleSlug => $slugs) {
            $roleId = $roles[$roleSlug] ?? null;

            if (! $roleId) {
                continue;
            }

            $rows = [];

            foreach ($slugs as $slug) {
                $permissionId = $permissionIds[$slug] ?? null;

                if ($permissionId) {
                    $rows[] = [
                        'role_id' => $roleId,
                        'permission_id' => $permissionId,
                    ];
                }
            }

            if ($rows !== []) {
                DB::table('permission_role')->upsert($rows, ['role_id', 'permission_id'], []);
            }
        }
    }
}
