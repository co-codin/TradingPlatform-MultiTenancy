<?php

use Carbon\CarbonImmutable;
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
//        DB::table('departments')->insert([
//            ['name' => 'conversion', 'title' => 'Conversion', 'created_at' => CarbonImmutable::now()],
//            ['name' => 'retention', 'title' => 'Retention', 'created_at' => CarbonImmutable::now()],
//        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        DB::table('departments')->whereIn('name', ['conversion', 'retention'])->delete();
    }
};
