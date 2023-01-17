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
        Schema::create('campaign_country', function (Blueprint $table) {
            $table->id();

            $table->foreignId('country_id')->constrained('public.countries')->references('id')->onDelete('CASCADE');
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
