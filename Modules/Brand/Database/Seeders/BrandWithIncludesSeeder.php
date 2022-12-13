<?php

declare(strict_types=1);

namespace Modules\Brand\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Brand\Models\Brand;
use Modules\Customer\Models\Customer;
use Modules\Department\Models\Department;
use Modules\Desk\Models\Desk;
use Modules\Geo\Database\Seeders\CountryTableSeeder;
use Modules\Geo\Models\Country;
use Modules\Role\Database\Seeders\RoleDatabaseSeeder;
use Modules\Role\Database\Seeders\UserTestAdminSeeder;

final class BrandWithIncludesSeeder extends Seeder
{
    public function run(): void
    {
        $brands = Brand::factory(3)->create();

        /** @var Brand $brand */
        foreach ($brands as $brand) {
            $brand->makeCurrent();
            $this->call(CountryTableSeeder::class);
            $countries = Country::get();

            $desks = Desk::factory(3)->create();
            $departments = Department::factory(3)->create();

            $customers = collect();

            for ($i = 0; $i < 3; $i++) {
                $customers = $customers->push(
                    Customer::factory()->create([
                        'country_id' => $countries->random()?->id,
                        'desk_id' => $desks->random()?->id,
                        'department_id' => $departments->random()?->id,
                    ])
                );
            }

            /** @var Customer $customer */
            foreach ($customers as $customer) {
                $users = collect([
                    $customer->affiliateUser,
                    $customer->conversionUser,
                    $customer->retentionUser,
                    $customer->complianceUser,
                    $customer->supportUser,
                    $customer->conversionManageUser,
                    $customer->retentionManageUser,
                    $customer->firstConversionUser,
                    $customer->firstRetentionUser,
                ])
                    ->unique('id')
                    ->pluck('id')
                    ->toArray();

                $brand->users()->sync($users);
                $customer->desk->users()->sync($users);
                $customer->department->users()->sync($users);
            }

            $this->call(
                RoleDatabaseSeeder::class,
            );
        }
    }
}
