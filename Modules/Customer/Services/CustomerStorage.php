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
        if (! $customer->update($dto->toArray())) {
            throw new LogicException(__('Can not update customer'));
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

    /**
     * Update or store customer.
     *
     * @param  CustomerDto  $dto
     * @return Customer
     */
    public function updateOrStore(CustomerDto $dto): Customer
    {
        if (! $customer = Customer::query()->updateOrCreate($dto->toArray())) {
            throw new LogicException(__('Can not update or create customer'));
        }

        return $customer;
    }
}
