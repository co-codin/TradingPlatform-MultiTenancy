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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('password');
            $table->boolean('is_active')->default(true);
            $table->bigInteger('target')->nullable();
            $table->foreignId('affiliate_id')->nullable()->constrained()->on('users');
            $table->boolean('show_on_scoreboards')->default(false);
            $table->nestedSet();
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamp('last_login')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('users');
        DB::statement("DROP TABLE IF EXISTS users CASCADE;");
    }
};
