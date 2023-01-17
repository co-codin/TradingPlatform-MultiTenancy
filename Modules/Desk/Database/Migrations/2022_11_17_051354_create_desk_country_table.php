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
        Schema::create('desk_country', function (Blueprint $table) {
            $table->primary(['desk_id', 'country_id'], 'id');
            $table->foreignId('desk_id')->constrained();
            $table->integer('country_id');
            $table->foreign('country_id')->on('public.countries')->references('id')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('desk_country');
    }
};
