<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_brand', function (Blueprint $table) {
            $table->primary(['user_id', 'brand_id'], 'id');
            $table->foreignId('user_id')->constrained();
            $table->foreignId('brand_id')->constrained();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_brand');
    }
};
