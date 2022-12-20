<?php

declare(strict_types=1);

namespace Modules\Communication\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Communication\Models\CommunicationExtension;
use Modules\Communication\Models\CommunicationProvider;

final class CommunicationProviderDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        CommunicationProvider::factory()->count(5)->create();
        CommunicationExtension::factory()->count(10)->create();
    }
}
