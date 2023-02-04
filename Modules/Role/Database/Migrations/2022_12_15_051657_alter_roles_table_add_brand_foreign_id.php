<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');
        $teams = config('permission.teams');

        Schema::table($tableNames['roles'], function (Blueprint $table) use ($teams, $columnNames) {
            if ($teams && empty($columnNames['team_foreign_key'] ?? null)) {
                throw new \Exception('Error: team_foreign_key on config/permission.php not loaded. Run [php artisan config:clear] and try again.');
            }

            $table->foreignId('brand_id')->nullable()->constrained();

            if ($teams || config('permission.testing')) {
                $table->dropUnique([$columnNames['team_foreign_key'], 'name', 'guard_name']);
                $table->unique([$columnNames['team_foreign_key'], 'name', 'guard_name', 'brand_id']);
            } else {
                $table->dropUnique(['name', 'guard_name']);
                $table->unique(['name', 'guard_name', 'brand_id']);
            }
        });

        Schema::table($tableNames['model_has_permissions'], function (Blueprint $table) use ($columnNames, $teams) {
            $table->foreignId('brand_id')->nullable()->constrained();

            $table->dropUnique('model_has_permissions_permission_model_type_unique');
            if ($teams) {
                $table->unique([$columnNames['team_foreign_key'], PermissionRegistrar::$pivotPermission, $columnNames['model_morph_key'], 'model_type', 'brand_id']);
            } else {
                $table->unique([PermissionRegistrar::$pivotPermission, $columnNames['model_morph_key'], 'model_type', 'brand_id']);
            }
        });

        Schema::table($tableNames['model_has_roles'], function (Blueprint $table) use ($columnNames, $teams) {
            $table->foreignId('brand_id')->nullable()->constrained();

            $table->dropUnique('model_has_roles_role_model_type_unique');
            if ($teams) {
                $table->unique([$columnNames['team_foreign_key'], PermissionRegistrar::$pivotRole, $columnNames['model_morph_key'], 'model_type', 'brand_id']);
            } else {
                $table->unique([PermissionRegistrar::$pivotRole, $columnNames['model_morph_key'], 'model_type', 'brand_id']);
            }
        });

        Schema::table($tableNames['role_has_permissions'], function (Blueprint $table) {
            $table->foreignId('brand_id')->nullable()->constrained();
            $table->dropUnique('role_has_permissions_permission_id_role_id_unique');
            $table->unique([PermissionRegistrar::$pivotPermission, PermissionRegistrar::$pivotRole, 'brand_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');
        $teams = config('permission.teams');

        Schema::table($tableNames['roles'], function (Blueprint $table) use ($teams, $columnNames) {
            if ($teams && empty($columnNames['team_foreign_key'] ?? null)) {
                throw new \Exception('Error: team_foreign_key on config/permission.php not loaded. Run [php artisan config:clear] and try again.');
            }

            if ($teams || config('permission.testing')) {
                $table->dropUnique([$columnNames['team_foreign_key'], 'name', 'guard_name', 'brand_id']);
                $table->unique([$columnNames['team_foreign_key'], 'name', 'guard_name']);
            } else {
                $table->dropUnique(['name', 'guard_name', 'brand_id']);
                $table->unique(['name', 'guard_name']);
            }

            $table->dropColumn('brand_id');
        });

        Schema::table($tableNames['model_has_permissions'], function (Blueprint $table) use ($columnNames, $teams) {
            if ($teams) {
                $table->dropUnique([$columnNames['team_foreign_key'], PermissionRegistrar::$pivotPermission, $columnNames['model_morph_key'], 'model_type', 'brand_id']);
                $table->unique([$columnNames['team_foreign_key'], PermissionRegistrar::$pivotPermission, $columnNames['model_morph_key'], 'model_type'],
                    'model_has_permissions_permission_model_type_unique');
            } else {
                $table->dropUnique([PermissionRegistrar::$pivotPermission, $columnNames['model_morph_key'], 'model_type', 'brand_id']);
                $table->unique([PermissionRegistrar::$pivotPermission, $columnNames['model_morph_key'], 'model_type'],
                    'model_has_permissions_permission_model_type_unique');
            }

            $table->dropColumn('brand_id');
        });

        Schema::table($tableNames['model_has_roles'], function (Blueprint $table) use ($columnNames, $teams) {
            if ($teams) {
                $table->dropUnique([$columnNames['team_foreign_key'], PermissionRegistrar::$pivotRole, $columnNames['model_morph_key'], 'model_type', 'brand_id']);
                $table->unique([$columnNames['team_foreign_key'], PermissionRegistrar::$pivotRole, $columnNames['model_morph_key'], 'model_type'],
                    'model_has_roles_role_model_type_unique');
            } else {
                $table->dropUnique([PermissionRegistrar::$pivotRole, $columnNames['model_morph_key'], 'model_type', 'brand_id']);
                $table->unique([PermissionRegistrar::$pivotRole, $columnNames['model_morph_key'], 'model_type'],
                    'model_has_roles_role_model_type_unique');
            }

            $table->dropColumn('brand_id');
        });

        Schema::table($tableNames['role_has_permissions'], function (Blueprint $table) {
            $table->dropUnique([PermissionRegistrar::$pivotPermission, PermissionRegistrar::$pivotRole, 'brand_id']);
            $table->unique([PermissionRegistrar::$pivotPermission, PermissionRegistrar::$pivotRole], 'role_has_permissions_permission_id_role_id_unique');

            $table->dropColumn('brand_id');
        });
    }
};
