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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->tinyInteger('gender');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone');
            $table->string('phone_2')->nullable();
            $table->timestamp('birthday')->nullable();

            $table->foreignId('subbrand_id')->nullable()->constrained('public.brands')->references('id');

            $table->foreignId('country_id')->constrained('public.countries')->references('id');
            $table->foreignId('currency_id')->nullable()
                ->constrained('public.currencies')
                ->references('id');

            $table->foreignId('language_id')->nullable()
                ->constrained('public.languages')
                ->references('id');
            $table->foreignId('supposed_language_id')->nullable()
                ->constrained('public.languages')
                ->references('id');
            $table->foreignId('platform_language_id')->nullable()
                ->constrained('public.languages')
                ->references('id');
            $table->foreignId('browser_language_id')->nullable()
                ->constrained('public.languages')
                ->references('id');

            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('timezone')->default('UTC');

            $table->tinyInteger('verification_status_id')->nullable();

            $table->boolean('is_demo')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_active_trading')->default(true);
            $table->boolean('is_test')->default(false);

            $table->foreignId('affiliate_user_id')->nullable()->constrained('public.users')->references('id');
            $table->foreignId('conversion_user_id')->nullable()->constrained('public.users')->references('id');
            $table->foreignId('retention_user_id')->nullable()->constrained('public.users')->references('id');
            $table->foreignId('compliance_user_id')->nullable()->constrained('public.users')->references('id');
            $table->foreignId('support_user_id')->nullable()->constrained('public.users')->references('id');
            $table->foreignId('conversion_manager_user_id')->nullable()->constrained('public.users')->references('id');
            $table->foreignId('retention_manager_user_id')->nullable()->constrained('public.users')->references('id');
            $table->foreignId('first_conversion_user_id')->nullable()->constrained('public.users')->references('id');
            $table->foreignId('first_retention_user_id')->nullable()->constrained('public.users')->references('id');

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
            $table->timestamp('banned_at')->nullable();

            // affiliation
            $table->foreignId('campaign_id')->nullable()->constrained()->on('public.campaigns');
            $table->string('offer_name')->nullable();
            $table->string('offer_url')->nullable();
            $table->string('comment_about_customer')->nullable();
            $table->string('source')->nullable();
            $table->string('click_id')->nullable();
            $table->string('free_param_1')->nullable();
            $table->string('free_param_2')->nullable();
            $table->string('free_param_3')->nullable();

            $table->unsignedFloat('balance')->nullable();
            $table->unsignedFloat('balance_usd')->nullable();
            $table->boolean('is_ftd')->default(false);

            $table->foreignId('conversion_sale_status_id')->nullable()->constrained()->on('sale_statuses');
            $table->foreignId('retention_sale_status_id')->nullable()->constrained()->on('sale_statuses');

            $table->softDeletes();
            $table->timestamps();
            $table->rememberToken();
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
