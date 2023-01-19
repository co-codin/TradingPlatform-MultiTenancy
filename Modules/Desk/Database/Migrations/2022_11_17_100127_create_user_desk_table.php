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
        Schema::create('user_desk', function (Blueprint $table) {
            $table->primary(['user_id', 'desk_id'], 'id');
            $table->integer('user_id');
            $table->foreign('user_id')->on('public.users')->references('id');
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
