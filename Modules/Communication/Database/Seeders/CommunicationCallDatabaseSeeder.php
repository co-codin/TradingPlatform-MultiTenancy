<?php

declare(strict_types=1);

namespace Modules\Communication\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Communication\Models\Call;

final class CommunicationCallDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Call::factory()->count(5)->create();
    }
}
