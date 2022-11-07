<?php

namespace Modules\Worker\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Worker\Models\Worker;

class WorkerDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // Make admin
        Worker::factory()->create([
            'email' => 'test@stoxtech.dev'
        ]);

        Worker::factory(10)->create();
    }
}
