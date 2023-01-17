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
        Schema::create('campaign_transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('affiliate_id')->constrained('public.users');
            $table->tinyInteger('type');
            $table->float('amount');
            $table->json('customer_ids');

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
        Schema::dropIfExists('campaign_transactions');
    }
};
