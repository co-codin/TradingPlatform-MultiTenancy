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

            $table->enum('type', ['deposit', 'withdrawal']);
            $table->foreignId('creator_id')->nullable();
            $table->foreignId('worker_id')->nullable()->constrained('public.users');
            $table->foreignId('customer_id')->constrained('customers');
            $table->foreignId('desk_id')->nullable()->constrained('desks');

            $table->foreignId('status_id')->constrained('transaction_statuses');
            $table->foreignId('currency_id')->constrained('currencies');
            $table->foreignId('mt5_type_id')->constrained('transaction_mt5_types');
            $table->foreignId('method_id')->constrained('transaction_methods');
            $table->foreignId('wallet_id')->constrained('transaction_wallets');

            $table->string('external_id')->nullable();
            $table->text('data')->nullable();
            $table->string('description')->nullable();

            $table->float('amount', 20, 10);
            $table->float('amount_usd', 10, 2);
            $table->float('amount_eur', 10, 2);

            $table->boolean('is_ftd')->default(false);
            $table->boolean('is_last_deposit')->default(false);
            $table->boolean('is_manual')->default(false);
            $table->boolean('is_test')->default(false);

            $table->json('settings')->nullable();

            $table->timestamp('approve_at')->nullable();
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
