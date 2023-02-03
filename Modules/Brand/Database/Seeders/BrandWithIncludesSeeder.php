<?php

declare(strict_types=1);

namespace Modules\Brand\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Brand\Models\Brand;
use Modules\Communication\Database\Seeders\CommunicationDatabaseSeeder;
use Modules\Communication\Database\Seeders\NotificationTemplateDatabaseSeeder;
use Modules\Customer\Models\Customer;
use Modules\Department\Database\Seeders\DepartmentDatabaseSeeder;
use Modules\Desk\Models\Desk;
use Modules\Role\Database\Seeders\ColumnsTableSeeder;
use Modules\Transaction\Database\Seeders\TransactionDatabaseSeeder;

final class BrandWithIncludesSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment('local')) {
            Brand::factory(3)->create();
        }

        $brands = Brand::all();

        /** @var Brand $brand */
        foreach ($brands as $brand) {
            $brand->makeCurrent();
            $this->beforeCustomers();

            Desk::factory(3)->create();
            $desks = Desk::get();

            $customers = collect();

            for ($i = 0; $i < 100; $i++) {
                $customerData = $brand->execute(function () use ($desks) {
                    return  Customer::factory()->make([
                        'desk_id' => $desks->random()?->id,
                    ]);
                });

                $customerData->save();

                $customers->push($customerData);
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
                ])->unique('id')->filter()->pluck('id')->toArray();

                $brand->users()->sync($users, false);
                $customer->desk->users()->sync($users, false);
                $customer->department->users()->sync($users, false);
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
