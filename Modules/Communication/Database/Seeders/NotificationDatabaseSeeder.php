<?php

declare(strict_types=1);

namespace Modules\Communication\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Communication\Models\DatabaseNotification;
use Modules\Customer\Models\Customer;

final class NotificationDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        for ($i = 0; $i < 20; $i++) {
            DatabaseNotification::factory()->forModel(
                Customer::inRandomOrder()->first() ?? Customer::factory()->create()
            )->create();
        }
    }
}
