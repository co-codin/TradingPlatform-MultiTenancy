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
        Schema::create('desk_language', function (Blueprint $table) {
            $table->primary(['desk_id', 'language_id'], 'id');
            $table->foreignId('desk_id')->constrained();
            $table->foreignId('language_id')->constrained('public.languages')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('desk_language');
    }
};
