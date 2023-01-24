<?php

declare(strict_types=1);

namespace Modules\Splitter\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Splitter\Models\Splitter;

class SplitterDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Splitter::factory()->count(5)->create();
    }
}
