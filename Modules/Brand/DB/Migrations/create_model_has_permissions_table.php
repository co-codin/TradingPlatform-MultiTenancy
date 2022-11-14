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
        Schema::create('model_has_permissions', function (Blueprint $table) {
            $table->bigInteger('permission_id')->unsigned()->nullable(false);
            $table->string('model_type')->nullable(false);
            $table->bigInteger('model_id')->unsigned()->nullable(false);

            $table->index(['model_type', 'model_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('model_has_permissions');
    }
};
