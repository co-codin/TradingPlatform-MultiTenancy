<?php

namespace Modules\Customer\Services;

use App\Services\Storage\Traits\SyncHelper;
use Illuminate\Support\Facades\Hash;
use LogicException;
use Modules\Campaign\Models\Campaign;
use Modules\Customer\Dto\CustomerDto;
use Modules\Customer\Events\CustomerEdited;
use Modules\Customer\Events\CustomerStored;
use Modules\Customer\Models\Customer;

final class CustomerStorage
{
    use SyncHelper;

    /**
     * Store customer.
     *
     * @param  CustomerDto  $dto
     * @return Customer
     */
    public function store(CustomerDto $dto): Customer
    {
        $attributes = $dto->toArray();
        $attributes['password'] = Hash::make($attributes['password']);

        if (isset($attributes['campaign_id']) && ! ($campaign = Campaign::find($attributes['campaign_id']))->is_active) {
            throw new LogicException(__('Can not create customer because the selected campaign ":name" is not active', ['name' => $campaign->name]));
        }

        $customer = Customer::create($attributes);

        if (! $customer) {
            throw new LogicException(__('Can not create customer'));
        }

        event(new CustomerStored($customer, dto: $dto));

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
        $attributes = $dto->toArray();

        if (isset($attributes['password'])) {
            $attributes['password'] = Hash::make($attributes['password']);
        }

        if (! $customer->update($attributes)) {
            throw new LogicException(__('Can not update customer'));
        }

        $this->syncBelongsToManyWithPivot($customer, $attributes, 'permissions');

        event(new CustomerEdited($customer, dto: $dto));

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
        if ($customer = Customer::query()->where('email', $dto->email)->first()) {
            $customer = $this->update($customer, $dto);
        } else {
            $customer = $this->store($dto);
        }

        return $customer;
    }
}
