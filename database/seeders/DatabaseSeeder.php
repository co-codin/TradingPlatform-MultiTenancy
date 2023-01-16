<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Brand\Database\Seeders\BrandDatabaseSeeder;
use Modules\Role\Database\Seeders\RoleDatabaseSeeder;
use Modules\User\Database\Seeders\UserDatabaseSeeder;
use Modules\Geo\Database\Seeders\GeoDatabaseSeeder;
use Modules\Campaign\Database\Seeders\CampaignDatabaseSeeder;
use Spatie\Multitenancy\Models\Tenant;

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
            CampaignDatabaseSeeder::class,
            BrandDatabaseSeeder::class,
            ModelsTableSeeder::class,
        ]);

        Tenant::checkCurrent()
            ? $this->runTenantSpecificSeeders()
            : $this->runLandlordSpecificSeeders();
    }

    public function runTenantSpecificSeeders()
    {
        // run tenant specific seeders / Если нужно будет
    }

    public function runLandlordSpecificSeeders()
    {
        // run landlord specific seeders / Если нужно будет
    }
}
