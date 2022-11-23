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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->tinyInteger('gender');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('phone2');

            $table->foreignId('country_id')->constrained();

            $table->foreignId('language_id')->nullable()->constrained();
            $table->foreignId('supposed_language_id')->nullable()->constrained();
            $table->foreignId('supposed_language_id')->nullable()->constrained();

            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->string('postal_code')->nullable();

            $table->tinyInteger('verification_status_id')->nullable();

            $table->boolean('is_active')->default(true);
            $table->boolean('is_active_trading')->default(true);
            $table->boolean('is_test')->default(false);

            $table->foreignId('affiliate_user_id')->nullable()->constrained()->on('users');
            $table->foreignId('conversion_user_id')->nullable()->constrained()->on('users');
            $table->foreignId('retention_user_id')->nullable()->constrained()->on('users');
            $table->foreignId('compliance_user_id')->nullable()->constrained()->on('users');
            $table->foreignId('support_user_id')->nullable()->constrained()->on('users');
            $table->foreignId('conversion_manager_user_id')->nullable()->constrained()->on('users');
            $table->foreignId('retention_manager_user_id')->nullable()->constrained()->on('users');
            $table->foreignId('first_conversion_user_id')->nullable()->constrained()->on('users');
            $table->foreignId('first_retention_user_id')->nullable()->constrained()->on('users');

            $table->foreignId('desk_id')->nullable()->constrained();
            $table->foreignId('department_id')->nullable()->constrained();

            $table->timestamp('last_online')->nullable();
            $table->timestamp('first_autologin_time')->nullable();
            $table->timestamp('first_login_time')->nullable();
            $table->timestamp('first_deposit_date')->nullable();
            $table->timestamp('last_approved_deposit_date')->nullable();
            $table->timestamp('last_pending_deposit_date')->nullable();
            $table->timestamp('last_pending_withdrawal_date')->nullable();
            $table->timestamp('last_communication_date')->nullable();

            // affiliation
//            $table->foreignId('campaign_id')->nullable()->constrained();
//            $table->string('offer_name')->nullable();
//            $table->string('offer_url')->nullable();
//            $table->string('comment_about_customer')->nullable();

            $table->unsignedFloat('balance')->nullable();
            $table->unsignedFloat('balance_usd')->nullable();

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
        Schema::dropIfExists('customers');
    }
};
