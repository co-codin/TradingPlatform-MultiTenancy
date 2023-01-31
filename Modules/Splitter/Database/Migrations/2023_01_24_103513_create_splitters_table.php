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
        Schema::create('splitters', function (Blueprint $table) {
            $table->id();

            $table->foreignId('brand_id')->constrained('public.brands');
            $table->string('name');
            $table->boolean('is_active')->default(false);
            $table->json('conditions')->nullable();
            $table->json('share_conditions')->nullable();
            $table->integer('position')->nullable();

            $table->softDeletes();
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
        Schema::dropIfExists('splitters');
    }
};
