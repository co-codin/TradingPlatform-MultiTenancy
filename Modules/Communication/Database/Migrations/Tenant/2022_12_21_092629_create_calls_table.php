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
        Schema::create('calls', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained('public.users');
            $table->morphs('sendcallable');
            $table->morphs('callable');
            $table->foreignId('provider_id')->constrained('communication_providers');
            $table->integer('duration')->default(0);
            $table->text('text');
            $table->tinyinteger('status')->default(0);

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
        Schema::dropIfExists('calls');
    }
};
