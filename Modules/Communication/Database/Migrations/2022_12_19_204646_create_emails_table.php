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
        Schema::create('emails', function (Blueprint $table) {
            $table->id();

            $table->foreignId('email_template_id')->references('id')->on('email_templates');
            $table->morphs('sendemailable');
            $table->morphs('emailable');
            $table->string('subject');
            $table->text('body');
            $table->boolean('sent_by_system')->default(true);
            $table->integer('user_id')->nullable();

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
        Schema::dropIfExists('emails');
    }
};
