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
        Schema::create('splitter_choice_user', function (Blueprint $table) {

            $table->foreignId('splitter_choice_id')->constrained('public.splitter_choices');
            $table->foreignId('user_id')->constrained('public.users');
            $table->integer('percentage');

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
