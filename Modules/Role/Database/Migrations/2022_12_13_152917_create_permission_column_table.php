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
    public function up(): void
    {
        Schema::create('permission_column', function (Blueprint $table) {
            $table->foreignId('permission_id')->constrained();
            $table->foreignId('column_id')->constrained();
            $table->foreignId('role_id')->constrained();
            $table->primary(['permission_id', 'column_id', 'role_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('permission_column');
    }
};
