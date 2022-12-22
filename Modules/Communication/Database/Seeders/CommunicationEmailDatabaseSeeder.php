<?php

declare(strict_types=1);

namespace Modules\Communication\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Communication\Models\Email;
use Modules\Communication\Models\EmailTemplates;

final class CommunicationEmailDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        EmailTemplates::factory()->count(5)->create();
        Email::factory()->count(5)->create();
    }
}
