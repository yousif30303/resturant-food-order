<?php

use App\Models\Admin;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->alignRolesTable();
        $this->alignPermissionsTable();
        $this->alignModelHasRolesTable();
        $this->alignModelHasPermissionsTable();
        $this->alignRoleHasPermissionsTable();
    }

    public function down(): void
    {
        //
    }

    private function alignRolesTable(): void
    {
        if (! Schema::hasTable('roles')) {
            return;
        }

        if (! Schema::hasColumn('roles', 'guard_name')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->string('guard_name')->default('admin')->after('name');
            });
        }

        if (Schema::hasColumn('roles', 'slug')) {
            DB::table('roles')->whereNotNull('slug')->update(['name' => DB::raw('slug')]);

            Schema::table('roles', function (Blueprint $table) {
                $table->dropUnique('roles_slug_unique');
                $table->dropColumn('slug');
            });
        }
    }

    private function alignPermissionsTable(): void
    {
        if (! Schema::hasTable('permissions')) {
            return;
        }

        if (! Schema::hasColumn('permissions', 'guard_name')) {
            Schema::table('permissions', function (Blueprint $table) {
                $table->string('guard_name')->default('admin')->after('name');
            });
        }

        if (Schema::hasColumn('permissions', 'slug')) {
            DB::table('permissions')->whereNotNull('slug')->update(['name' => DB::raw('slug')]);

            Schema::table('permissions', function (Blueprint $table) {
                $table->dropUnique('permissions_slug_unique');
                $table->dropColumn('slug');
            });
        }
    }

    private function alignModelHasRolesTable(): void
    {
        if (! Schema::hasTable('model_has_roles')) {
            Schema::create('model_has_roles', function (Blueprint $table) {
                $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
                $table->string('model_type');
                $table->unsignedBigInteger('model_id');

                $table->index(['model_id', 'model_type'], 'model_has_roles_model_id_model_type_index');
                $table->primary(['role_id', 'model_id', 'model_type'], 'model_has_roles_role_model_type_primary');
            });
        }

        if (Schema::hasTable('admin_role')) {
            DB::table('admin_role')
                ->orderBy('admin_id')
                ->get()
                ->each(function (object $row): void {
                    DB::table('model_has_roles')->updateOrInsert([
                        'role_id' => $row->role_id,
                        'model_type' => Admin::class,
                        'model_id' => $row->admin_id,
                    ]);
                });
        }
    }

    private function alignModelHasPermissionsTable(): void
    {
        if (Schema::hasTable('model_has_permissions')) {
            return;
        }

        Schema::create('model_has_permissions', function (Blueprint $table) {
            $table->foreignId('permission_id')->constrained('permissions')->cascadeOnDelete();
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');

            $table->index(['model_id', 'model_type'], 'model_has_permissions_model_id_model_type_index');
            $table->primary(['permission_id', 'model_id', 'model_type'], 'model_has_permissions_permission_model_type_primary');
        });
    }

    private function alignRoleHasPermissionsTable(): void
    {
        if (! Schema::hasTable('role_has_permissions')) {
            Schema::create('role_has_permissions', function (Blueprint $table) {
                $table->foreignId('permission_id')->constrained('permissions')->cascadeOnDelete();
                $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();

                $table->primary(['permission_id', 'role_id'], 'role_has_permissions_permission_id_role_id_primary');
            });
        }

        if (Schema::hasTable('permission_role')) {
            DB::table('permission_role')
                ->orderBy('role_id')
                ->get()
                ->each(function (object $row): void {
                    DB::table('role_has_permissions')->updateOrInsert([
                        'permission_id' => $row->permission_id,
                        'role_id' => $row->role_id,
                    ]);
                });
        }
    }
};
