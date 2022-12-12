<?php

declare(strict_types=1);

namespace Modules\TelephonyProvider\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\TelephonyProvider\Models\TelephonyExtension;
use Modules\TelephonyProvider\Models\TelephonyProvider;

final class TelephonyProviderDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        TelephonyProvider::factory()->count(10)->create();
        TelephonyExtension::factory()->count(10)->create();
    }
}
