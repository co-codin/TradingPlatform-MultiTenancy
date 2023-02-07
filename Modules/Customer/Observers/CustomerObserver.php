<?php

declare(strict_types=1);

namespace Modules\Customer\Observers;

use Exception;
use Modules\Customer\Jobs\CopyCustomerJob;
use Modules\Customer\Models\Customer;
use Modules\Splitter\Jobs\CustomerDistributionJob;
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

        if ($customer->isDirty('is_ftd')) {
            CustomerDistributionJob::dispatch(
                $customer->id,
                Tenant::current()
            );
        }

        $this->validateConversionDepartment($customer);
        $this->validateRetentionDepartment($customer);
    }

    /**
     * Handle the Customer "created" event.
     *
     * @param  Customer  $customer
     * @return void
     */
    public function created(Customer $customer): void
    {
        CustomerDistributionJob::dispatch(
            $customer->id,
            Tenant::current()
        );
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
            (
                $customer->getOriginal('retention_sale_status_id') !== $customer->retention_sale_status_id
                && $customer->getOriginal('retention_user_id') !== $customer->retention_user_id
                && $customer->getOriginal('retention_manager_user_id') !== $customer->retention_manager_user_id
                && $customer->getOriginal('first_retention_user_id') !== $customer->first_retention_user_id
            )
            && (! $customer->department || $customer->department->isConversion())
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
            (
                $customer->getOriginal('conversion_sale_status_id') !== $customer->conversion_sale_status_id
                && $customer->getOriginal('conversion_user_id') !== $customer->conversion_user_id
                && $customer->getOriginal('conversion_manager_user_id') !== $customer->conversion_manager_user_id
                && $customer->getOriginal('first_conversion_user_id') !== $customer->first_conversion_user_id
            )
            && (! $customer->department || $customer->department->isRetention())
        ) {
            throw new Exception(__("You can't set conversion options for a retention department."));
        }
    }
}
