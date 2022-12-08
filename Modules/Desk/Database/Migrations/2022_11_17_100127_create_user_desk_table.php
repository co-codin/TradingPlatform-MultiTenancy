<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_desk', function (Blueprint $table) {
            $table->primary(['user_id', 'desk_id'], 'id');
            $table->foreignId('user_id')->constrained()->on('public.users');
            $table->foreignId('desk_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_desk');
    }
};
