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
        Schema::create('worker_brand', function (Blueprint $table) {
            $table->primary(['worker_id', 'brand_id'], 'id');
            $table->foreignId('worker_id')->constrained();
            $table->foreignId('brand_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('worker_brand');
    }
};
