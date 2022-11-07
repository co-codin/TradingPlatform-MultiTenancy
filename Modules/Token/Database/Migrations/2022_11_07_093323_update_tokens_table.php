<?php
declare(strict_types=1);

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
    public function up(): void
    {
        Schema::table('tokens', static function(Blueprint $table) {
            if (Schema::hasColumn('tokens', 'id')) {
                $table->string('ip')->nullable(false)->default('0.0.0.0')->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('tokens', static function(Blueprint $table) {
            if (Schema::hasColumn('tokens', 'id')) {
                $table->string('ip')->nullable(false)->default(null)->change();
            }
        });
    }
};
