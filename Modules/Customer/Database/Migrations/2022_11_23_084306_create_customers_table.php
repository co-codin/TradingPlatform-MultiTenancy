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
            $table->string('phone2')->nullable();
            $table->timestamp('birthday')->nullable();

            $table->foreignId('country_id')->constrained('public.countries')->references('id')->onDelete('CASCADE');
            $table->foreignId('currency_id')->constrained('public.currencies')->references('id')->onDelete('CASCADE');

            $table->foreignId('language_id')->nullable()
                ->constrained('public.languages')
                ->references('id')
                ->onDelete('SET NULL');
            $table->foreignId('supposed_language_id')->nullable()
                ->constrained('public.languages')
                ->references('id')
                ->onDelete('SET NULL');
            $table->foreignId('platform_language_id')->nullable()
                ->constrained('public.languages')
                ->references('id')
                ->onDelete('SET NULL');
            $table->foreignId('browser_language_id')->nullable()
                ->constrained('public.languages')
                ->references('id')
                ->onDelete('SET NULL');

            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->string('postal_code')->nullable();

            $table->tinyInteger('verification_status_id')->nullable();

            $table->boolean('is_demo')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_active_trading')->default(true);
            $table->boolean('is_test')->default(false);

            $table->integer('affiliate_user_id')->nullable();
            $table->integer('conversion_user_id')->nullable();
            $table->integer('retention_user_id')->nullable();
            $table->integer('compliance_user_id')->nullable();
            $table->integer('support_user_id')->nullable();
            $table->integer('conversion_manager_user_id')->nullable();
            $table->integer('retention_manager_user_id')->nullable();
            $table->integer('first_conversion_user_id')->nullable();
            $table->integer('first_retention_user_id')->nullable();

            $table->foreign('affiliate_user_id')->on('public.users')->references('id')->onDelete('SET NULL');
            $table->foreign('conversion_user_id')->on('public.users')->references('id')->onDelete('SET NULL');
            $table->foreign('retention_user_id')->on('public.users')->references('id')->onDelete('SET NULL');
            $table->foreign('compliance_user_id')->on('public.users')->references('id')->onDelete('SET NULL');
            $table->foreign('support_user_id')->on('public.users')->references('id')->onDelete('SET NULL');
            $table->foreign('conversion_manager_user_id')->on('public.users')->references('id')->onDelete('SET NULL');
            $table->foreign('retention_manager_user_id')->on('public.users')->references('id')->onDelete('SET NULL');
            $table->foreign('first_conversion_user_id')->on('public.users')->references('id')->onDelete('SET NULL');
            $table->foreign('first_retention_user_id')->on('public.users')->references('id')->onDelete('SET NULL');

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

            $table->string('timezone')->nullable();

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
