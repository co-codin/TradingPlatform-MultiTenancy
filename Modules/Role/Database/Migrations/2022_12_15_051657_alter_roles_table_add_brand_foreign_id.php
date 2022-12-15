<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles', function (Blueprint $table) {
            $columnNames = config('permission.column_names');
            $teams = config('permission.teams');

            if ($teams && empty($columnNames['team_foreign_key'] ?? null)) {
                throw new \Exception('Error: team_foreign_key on config/permission.php not loaded. Run [php artisan config:clear] and try again.');
            }

            if ($teams || config('permission.testing')) {
                $table->dropUnique([$columnNames['team_foreign_key'], 'name', 'guard_name']);
            } else {
                $table->dropUnique(['name', 'guard_name']);
            }

            $table->foreignId('brand_id')->nullable()->constrained();

            if ($teams || config('permission.testing')) {
                $table->unique([$columnNames['team_foreign_key'], 'name', 'guard_name', 'brand_id']);
            } else {
                $table->unique(['name', 'guard_name', 'brand_id']);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roles', function (Blueprint $table) {
            $columnNames = config('permission.column_names');
            $teams = config('permission.teams');

            if ($teams && empty($columnNames['team_foreign_key'] ?? null)) {
                throw new \Exception('Error: team_foreign_key on config/permission.php not loaded. Run [php artisan config:clear] and try again.');
            }

            if ($teams || config('permission.testing')) {
                $table->dropUnique([$columnNames['team_foreign_key'], 'name', 'guard_name', 'brand_id']);
            } else {
                $table->dropUnique(['name', 'guard_name', 'brand_id']);
            }

            $table->dropColumn('brand_id');

            if ($teams || config('permission.testing')) {
                $table->unique([$columnNames['team_foreign_key'], 'name', 'guard_name']);
            } else {
                $table->unique(['name', 'guard_name']);
            }
        });
    }
};
