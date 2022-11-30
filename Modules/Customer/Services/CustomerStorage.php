<?php

namespace Modules\Customer\Services;

use Exception;
use Modules\Customer\Models\Customer;

class CustomerStorage
{
    /**
     * @param  Customer  $customer
     * @param  array  $attributes
     * @return Customer
     *
     * @throws Exception
     */
    public function update(Customer $customer, array $attributes): Customer
    {
        if (! $customer->update($attributes)) {
            throw new Exception('Cant update customer');
        }

        return $customer;
    }
}
