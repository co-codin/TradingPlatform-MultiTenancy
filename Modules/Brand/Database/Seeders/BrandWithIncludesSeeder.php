<?php

declare(strict_types=1);

namespace Modules\Brand\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Brand\Models\Brand;
use Modules\Communication\Database\Seeders\CommunicationDatabaseSeeder;
use Modules\Communication\Database\Seeders\NotificationDatabaseSeeder;
use Modules\Customer\Models\Customer;
use Modules\Department\Models\Department;
use Modules\Desk\Models\Desk;
use Modules\Geo\Database\Seeders\CountryTableSeeder;
use Modules\Geo\Models\Country;

final class BrandWithIncludesSeeder extends Seeder
{
    public function run(): void
    {
        $brands = Brand::factory(3)->create();

        /** @var Brand $brand */
        foreach ($brands as $brand) {
            $brand->makeCurrent();
            $this->beforeCustomers();

            $countries = Country::get();
            $desks = Desk::factory(3)->create();
            $departments = Department::factory(3)->create();

            $customers = collect();

            for ($i = 0; $i < 3; $i++) {
                $customerData = $brand->execute(function () use ($countries, $desks, $departments) {
                    return  Customer::factory()->make([
                        'country_id' => $countries->random()?->id,
                        'desk_id' => $desks->random()?->id,
                        'department_id' => $departments->random()?->id,
                    ]);
                });

                $customerData->save();

                $customers = $customers->push($customerData);
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

            $this->afterCustomers();
            $brand->forget();
        }
    }

    private function beforeCustomers(): void
    {
        $this->call([
            CountryTableSeeder::class,
            CommunicationDatabaseSeeder::class,
        ]);
    }

    private function afterCustomers(): void
    {
        $this->call([
            NotificationDatabaseSeeder::class,
        ]);
    }
}
