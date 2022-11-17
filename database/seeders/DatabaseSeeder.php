<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Brand\Database\Seeders\BrandDatabaseSeeder;
use Modules\Geo\Database\Seeders\GeoDatabaseSeeder;
use Modules\Role\Database\Seeders\RoleDatabaseSeeder;
use Modules\User\Database\Seeders\UserDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserDatabaseSeeder::class);
        $this->call(BrandDatabaseSeeder::class);
//        $this->call(GeoDatabaseSeeder::class);
//        $this->call(RoleDatabaseSeeder::class);
    }
}
