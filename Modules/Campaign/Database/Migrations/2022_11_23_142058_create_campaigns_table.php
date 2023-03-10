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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();

            $table->foreignId('affiliate_id')->constrained('public.users');
            $table->string('name');
            $table->unsignedFloat('cpa');
            $table->json('working_hours');
            $table->unsignedInteger('daily_cap');
            $table->unsignedFloat('crg');
            $table->boolean('is_active')->default(true);
            $table->boolean('phone_verification')->default(false);
            $table->float('balance');
            $table->integer('monthly_cr');
            $table->integer('monthly_pv');
            $table->unsignedFloat('crg_cost');
            $table->unsignedFloat('ftd_cost');

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
        Schema::dropIfExists('campaigns');
    }
};
