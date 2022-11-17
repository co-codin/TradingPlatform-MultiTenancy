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
        Schema::create('desk_language', function (Blueprint $table) {
            $table->primary(['desk_id', 'language_id'], 'id');
            $table->foreignId('desk_id')->constrained();
            $table->foreignId('language_id')->constrained();
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
