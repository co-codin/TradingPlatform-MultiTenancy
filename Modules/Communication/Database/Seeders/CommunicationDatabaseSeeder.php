<?php

namespace Modules\Communication\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Communication\Models\Email;
use Modules\Communication\Models\EmailTemplates;

class CommunicationDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EmailTemplates::factory()->count(5)->create();
        Email::factory()->count(5)->create();
    }
}
