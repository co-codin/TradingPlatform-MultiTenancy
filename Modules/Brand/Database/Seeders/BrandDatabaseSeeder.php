<?php

declare(strict_types=1);

namespace Modules\Brand\Database\Seeders;

use Illuminate\Database\Seeder;

final class BrandDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        if (app()->environment('local', 'dev', 'development')) {
            $this->call(
                BrandWithIncludesSeeder::class,
            );
        }
    }
}
