<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Brand\Database\Seeders\BrandDatabaseSeeder;
use Modules\Brand\Models\Brand;
use Modules\Campaign\Database\Seeders\CampaignDatabaseSeeder;
use Modules\Currency\Database\Seeders\CurrencyDatabaseSeeder;
use Modules\Geo\Database\Seeders\GeoDatabaseSeeder;
use Modules\Language\Database\Seeders\LanguageDatabaseSeeder;
use Modules\Role\Database\Seeders\RoleDatabaseSeeder;
use Modules\Role\Database\Seeders\RolePermissionColumnsSeeder;
use Modules\Splitter\Database\Seeders\SplitterDatabaseSeeder;
use Modules\User\Database\Seeders\UserDatabaseSeeder;
use Modules\User\Models\User;
use Modules\User\Models\WorkerInfo;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $brand = Brand::first()->makeCurrent();
        $user = User::factory()->create();
        $user->workerInfo()->updateOrCreate([], WorkerInfo::factory()->raw());
        dump($user->workerInfo->first_name);
        $user->workerInfo()->updateOrCreate([], WorkerInfo::factory()->raw());
        dd($user->workerInfo);
        $this->call([
            CurrencyDatabaseSeeder::class,
            LanguageDatabaseSeeder::class,
            RoleDatabaseSeeder::class,
            UserDatabaseSeeder::class,
            GeoDatabaseSeeder::class,
            BrandDatabaseSeeder::class,
            ModelsTableSeeder::class,
            RolePermissionColumnsSeeder::class,
            CampaignDatabaseSeeder::class,
            SplitterDatabaseSeeder::class,
        ]);
    }
}
