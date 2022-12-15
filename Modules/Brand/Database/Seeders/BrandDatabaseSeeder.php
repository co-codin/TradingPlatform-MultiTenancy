<?php

declare(strict_types=1);

namespace Modules\Brand\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Brand\Models\Brand;
use Modules\Customer\Models\Customer;
use Modules\User\Models\User;

final class BrandDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call(
            BrandWithIncludesSeeder::class,
        );

//        if (app()->environment('local', 'dev', 'development')) {
//            $this->call(
//                BrandWithIncludesSeeder::class,
//            );
//        }
    }
}
