<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Brand\Database\Seeders\BrandDatabaseSeeder;
use Modules\Campaign\Database\Seeders\CampaignDatabaseSeeder;
use Modules\Geo\Database\Seeders\GeoDatabaseSeeder;
use Modules\Role\Database\Seeders\RoleDatabaseSeeder;
use Modules\User\Database\Seeders\UserDatabaseSeeder;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call([
            RoleDatabaseSeeder::class,
            UserDatabaseSeeder::class,
            GeoDatabaseSeeder::class,
            BrandDatabaseSeeder::class,
            ModelsTableSeeder::class,
            CampaignDatabaseSeeder::class,
        ]);
    }
}
