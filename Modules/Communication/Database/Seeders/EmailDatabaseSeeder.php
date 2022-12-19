<?php

namespace Modules\Communication\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Communication\Models\Email;

class EmailDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Email::factory()->count(5)->create();
        // Model::unguard();

        // $this->call("OthersTableSeeder");
    }
}
