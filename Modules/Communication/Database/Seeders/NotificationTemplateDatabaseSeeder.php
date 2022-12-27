<?php

declare(strict_types=1);

namespace Modules\Communication\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Communication\Models\NotificationTemplate;

final class NotificationTemplateDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        NotificationTemplate::factory()->count(20)->create();
    }
}
