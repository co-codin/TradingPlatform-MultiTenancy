<?php

declare(strict_types=1);

namespace Modules\Customer\Services;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use Modules\Customer\Dto\CustomerDto;
use Modules\Customer\Models\Customer;
use Modules\Customer\Repositories\CustomerRepository;
use Modules\User\Services\Traits\HasAuthUser;

final class CustomerBanService
{
    use HasAuthUser;

    /**
     * @param CustomerStorage $customerStorage
     * @param CustomerRepository $customerRepository
     */
    final public function __construct(
        protected CustomerStorage $customerStorage,
        protected CustomerRepository $customerRepository
    ) {
    }

    /**
     * Ban customer.
     *
     * @param Customer $customer
     * @return Customer|null
     * @throws Exception
     */
    final public function banCustomer(Customer $customer): ?Customer
    {
        if ($this->authUser?->can('ban', $customer)) {
            return $this->customerStorage->update($customer, new CustomerDto(['banned_at' => Carbon::now()->toDateTimeString()]));
        }

        return null;
    }

    /**
     * Ban customers.
     *
     * @param array $customersData
     * @return Collection
     * @throws Exception
     */
    final public function banCustomers(array $customersData): Collection
    {
        $bannedCustomers = collect();

        foreach ($customersData as $customerData) {
            $customer = $this->customerRepository->find($customerData['id']);

            $bannedCustomers->push($this->banCustomer($customer));
        }

        return $bannedCustomers;
    }

    /**
     * Unban customer.
     *
     * @param Customer $customer
     * @return Customer|null
     * @throws Exception
     */
    final public function unbanCustomer(Customer $customer): ?Customer
    {
        if ($this->authUser?->can('unban', $customer)) {
            return $this->customerStorage->update($customer, new CustomerDto(['banned_at' => null]));
        }

        return null;
    }

    /**
     * Unban customers.
     *
     * @param array $customersData
     * @return Collection
     * @throws Exception
     */
    final public function unbanCustomers(array $customersData): Collection
    {
        $unbannedCustomers = collect();

        foreach ($customersData as $customerData) {
            $customer = $this->customerRepository->find($customerData['id']);

            $unbannedCustomers->push($this->unbanCustomer($customer));
        }

        return $unbannedCustomers;
    }
}
