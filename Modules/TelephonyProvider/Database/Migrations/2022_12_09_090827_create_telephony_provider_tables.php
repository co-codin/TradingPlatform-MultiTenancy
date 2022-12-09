<?php

declare(strict_types=1);

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
        Schema::create('telephony_providers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_default')->default(false);
            $table->foreignId('user_id')->constrained('public.users');
            $table->timestamps();
        });

        Schema::create('telephony_extensions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('provider_id')->constrained('telephony_providers');
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
        Schema::dropIfExists('telephony_providers');
        Schema::dropIfExists('telephony_extensions');
    }
};
