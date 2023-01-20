<?php

declare(strict_types=1);

namespace Modules\Customer\Observers;

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
    }
}
