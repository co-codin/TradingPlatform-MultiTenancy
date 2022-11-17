<?php

namespace Modules\Brand\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Brand\Models\Brand;
use Modules\User\Models\User;

class BrandDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Brand::factory()->count(50)->create()->each(function ($brand) {
            $brand->users()->sync(User::factory()->count(10)->create()->pluck('id'));
        });
    }
}
