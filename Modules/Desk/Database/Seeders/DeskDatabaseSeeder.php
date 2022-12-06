<?php

namespace Modules\Desk\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Desk\Models\Desk;

class DeskDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Desk::factory()->count(10)->create();
    }
}
