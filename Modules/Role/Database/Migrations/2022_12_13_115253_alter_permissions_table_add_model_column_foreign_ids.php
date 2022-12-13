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
        Schema::table('permissions', function (Blueprint $table) {
            $table->integer('model_id')->nullable();
            $table->integer('action_id')->nullable();

            $table->foreign('model_id')->on('public.models')->references('id')->onDelete('CASCADE');
            $table->foreign('action_id')->on('public.actions')->references('id')->onDelete('CASCADE');

            $table->unique(['model_id', 'action_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropColumn('model_id');
            $table->dropColumn('action_id');
        });
    }
};
