<?php

declare(strict_types=1);

namespace Modules\Customer\Observers;

use Exception;
use Modules\Customer\Jobs\CopyCustomerJob;
use Modules\Customer\Models\Customer;
use Spatie\Multitenancy\Models\Tenant;

final class CustomerObserver
{
    /**
     * Handle the Customer "saving" event.
     *
     * @param  Customer  $customer
     * @return void
     *
     * @throws Exception
     */
    public function saving(Customer $customer): void
    {
        if ($customer->isDirty('subbrand_id') && $customer->subbrand_id) {
            CopyCustomerJob::dispatch(
                $customer->id,
                Tenant::current(),
                $customer->subbrand_id
            );
        }

        $this->validateConversionDepartment($customer);
        $this->validateRetentionDepartment($customer);
    }

    /**
     * Validate conversion department.
     *
     * @param  Customer  $customer
     * @return void
     *
     * @throws Exception
     */
    private function validateConversionDepartment(Customer $customer): void
    {
        if (
            $customer->department->isConversion()
            && $customer->isDirty([
                'retention_status_id',
                'retention_user_id',
                'retention_manager_user_id',
                'first_retention_user_id',
            ])
        ) {
            throw new Exception(__("You can't set retention options for a conversion department."));
        }
    }

    /**
     * Validate retention department.
     *
     * @param  Customer  $customer
     * @return void
     *
     * @throws Exception
     */
    private function validateRetentionDepartment(Customer $customer): void
    {
        if (
            $customer->department->isRetention()
            && $customer->isDirty([
                'conversion_status_id',
                'conversion_user_id',
                'conversion_manager_user_id',
                'first_conversion_user_id',
            ])
        ) {
            throw new Exception(__("You can't set conversion options for a retention department."));
        }
    }
}
