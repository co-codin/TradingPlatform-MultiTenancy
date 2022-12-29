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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->float('value');
            $table->float('value_usd');
            $table->boolean('is_ftd')->default(false);

            $table->foreignId('customer_id')->constrained()->on('customers');
            $table->foreignId('status_id')->constrained()->on('transaction_statuses');
            $table->foreignId('mt5_type_id')->constrained()->on('transaction_mt5_types');
            $table->foreignId('method_id')->constrained()->on('transaction_methods');

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
        Schema::dropIfExists('transactions');
    }
};
