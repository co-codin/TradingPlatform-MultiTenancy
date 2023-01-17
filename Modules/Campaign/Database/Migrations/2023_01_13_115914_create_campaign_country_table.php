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
        Schema::create('campaign_country', function (Blueprint $table) {
            $table->id();

            $table->integer('country_id');
            $table->foreign('country_id')->on('public.countries')->references('id')->onDelete('CASCADE');
            $table->foreignId('campaign_id')->constrained();
            $table->unique(['country_id', 'campaign_id']);

            $table->float('cpa');
            $table->float('crg');
            $table->json('working_days');
            $table->json('working_hours');
            $table->unsignedInteger('daily_cap');

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
        Schema::dropIfExists('campaign_country');
    }
};
