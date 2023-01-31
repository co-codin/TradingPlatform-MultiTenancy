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
        Schema::create('splitter_choice_user', function (Blueprint $table) {
            $table->foreignId('splitter_choice_id')->constrained('public.splitter_choices');
            $table->foreignId('user_id')->constrained('public.users');
            $table->integer('percentage');
            $table->integer('cap_per_day');
            $table->integer('percentage_per_day');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('splitter_choice_user');
    }
};
