<?php

namespace Modules\Communication\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Communication\Models\EmailTemplates;

class EmailTemplatesDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EmailTemplates::factory()->count(5)->create();
        // Model::unguard();

        // $this->call("OthersTableSeeder");
    }
}
