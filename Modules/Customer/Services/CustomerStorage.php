<?php

namespace Modules\Customer\Services;

use LogicException;
use Modules\Customer\Dto\CustomerDto;
use Modules\Customer\Models\Customer;

class CustomerStorage
{
    /**
     * Store customer.
     *
     * @param  CustomerDto  $dto
     * @return Customer
     */
    public function store(CustomerDto $dto): Customer
    {
        $customer = Customer::create($dto->toArray());

        if (! $customer) {
            throw new LogicException(__('Can not create customer'));
        }

        return $customer;
    }

    /**
     * Update customer.
     *
     * @param  Customer  $customer
     * @param  CustomerDto  $dto
     * @return Customer
     *
     * @throws LogicException
     */
    public function update(Customer $customer, CustomerDto $dto): Customer
    {
        $updateException = new LogicException(__('Can not update customer'));

//        if ($customer->first_retention_user_id) {
//            $dto->first_retention_user_id = null;
//        } elseif ($dto->retention_user_id && ! $dto->first_retention_user_id) {
//            $dto->first_retention_user_id = $dto->affiliate_user_id;
//        } elseif ($dto->retention_user_id !== $dto->first_retention_user_id) {
//            throw $updateException;
//        }

        if (! $customer->update($dto->toArray())) {
            throw $updateException;
        }

        return $customer;
    }

    /**
     * Delete customer.
     *
     * @param  Customer  $customer
     * @return bool
     */
    public function delete(Customer $customer): bool
    {
        if (! $customer->delete()) {
            throw new LogicException(__('Can not delete customer'));
        }

        return true;
    }
}
