<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Brand\Database\Seeders\BrandDatabaseSeeder;
use Modules\Campaign\Database\Seeders\CampaignDatabaseSeeder;
use Modules\Currency\Database\Seeders\CurrencyDatabaseSeeder;
use Modules\Geo\Database\Seeders\GeoDatabaseSeeder;
use Modules\Language\Database\Seeders\LanguageDatabaseSeeder;
use Modules\Role\Database\Seeders\RoleDatabaseSeeder;
use Modules\User\Database\Seeders\UserDatabaseSeeder;
use Modules\User\Database\Seeders\UserPermissionColumnsSeeder;

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
            CurrencyDatabaseSeeder::class,
            LanguageDatabaseSeeder::class,
            RoleDatabaseSeeder::class,
            UserDatabaseSeeder::class,
            GeoDatabaseSeeder::class,
            BrandDatabaseSeeder::class,
            ModelsTableSeeder::class,
            UserPermissionColumnsSeeder::class,
            CampaignDatabaseSeeder::class,
        ]);
    }
}
