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
            $table->foreignId('model_id')->constrained()->on('public.models')->onDelete('CASCADE');
            $table->foreignId('action_id')->constrained()->on('public.actions')->onDelete('CASCADE');

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
