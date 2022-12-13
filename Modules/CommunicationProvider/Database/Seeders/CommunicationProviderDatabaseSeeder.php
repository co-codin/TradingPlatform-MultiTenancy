<?php

declare(strict_types=1);

namespace Modules\CommunicationProvider\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\CommunicationProvider\Models\CommunicationExtension;
use Modules\CommunicationProvider\Models\CommunicationProvider;

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

        CommunicationProvider::factory()->count(10)->create();
        CommunicationExtension::factory()->count(10)->create();
    }
}
