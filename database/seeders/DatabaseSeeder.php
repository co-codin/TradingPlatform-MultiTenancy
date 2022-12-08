<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Brand\Database\Seeders\BrandDatabaseSeeder;
use Modules\Geo\Database\Seeders\GeoDatabaseSeeder;
use Modules\Role\Database\Seeders\RoleDatabaseSeeder;
use Modules\User\Database\Seeders\UserDatabaseSeeder;
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
            //            GeoDatabaseSeeder::class,
        ]);

        if (config('app.APP_ENV') != 'production') {
            $this->call(BrandDatabaseSeeder::class);
        }

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
