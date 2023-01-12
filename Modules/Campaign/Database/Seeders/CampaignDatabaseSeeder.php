<?php

declare(strict_types=1);

namespace Modules\Campaign\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Campaign\Models\Campaign;

final class CampaignDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Campaign::factory()->count(5)->create();
    }
}
