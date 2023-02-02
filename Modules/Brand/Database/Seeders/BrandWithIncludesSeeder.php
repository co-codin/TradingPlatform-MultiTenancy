<?php

declare(strict_types=1);

namespace Modules\Brand\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Brand\Models\Brand;
use Modules\Communication\Database\Seeders\CommunicationDatabaseSeeder;
use Modules\Communication\Database\Seeders\NotificationTemplateDatabaseSeeder;
use Modules\Customer\Models\Customer;
use Modules\Department\Database\Seeders\DepartmentDatabaseSeeder;
use Modules\Department\Models\Department;
use Modules\Desk\Models\Desk;
use Modules\Role\Database\Seeders\ColumnsTableSeeder;
use Modules\Transaction\Database\Seeders\TransactionDatabaseSeeder;

final class BrandWithIncludesSeeder extends Seeder
{
    public function run(): void
    {
        Brand::factory(3)->create();
        $brands = Brand::query()->get();

        /** @var Brand $brand */
        foreach ($brands as $brand) {
            $brand->makeCurrent();
            $this->beforeCustomers();

            $desks = Desk::factory(3)->create();

            $departments = Department::get();

            $customers = collect();

            for ($i = 0; $i < 10; $i++) {
                $customerData = $brand->execute(function () use ($desks, $departments) {
                    return  Customer::factory()->make([
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

        $this->afterBrands();
    }

    private function beforeCustomers(): void
    {
        $this->call([
            CommunicationDatabaseSeeder::class,
            DepartmentDatabaseSeeder::class,
        ]);
    }

    private function afterCustomers(): void
    {
        $this->call([
            NotificationTemplateDatabaseSeeder::class,
            TransactionDatabaseSeeder::class,
        ]);
    }

    private function afterBrands(): void
    {
        $brand = Brand::first();
        $brand->makeCurrent();
        $this->call(ColumnsTableSeeder::class);
        $brand->forget();
    }
}
