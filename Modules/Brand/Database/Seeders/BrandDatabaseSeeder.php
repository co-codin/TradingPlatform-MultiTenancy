<?php

declare(strict_types=1);

namespace Modules\Brand\Database\Seeders;

use App\Services\Tenant\Manager;
use Illuminate\Database\Seeder;
use Modules\Brand\Models\Brand;
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
        Brand::factory()->count(3)->create()->each(function ($brand) {
            $brand->users()->sync(User::factory()->count(10)->create()->pluck('id'));
        });
    }
}
