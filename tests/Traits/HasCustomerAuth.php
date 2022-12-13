<?php

declare(strict_types=1);

namespace Tests\Traits;

use Modules\Customer\Models\Customer;
use Modules\Role\Contracts\PermissionEnum;
use Modules\Role\Models\Permission;

trait HasCustomerAuth
{
    protected ?Customer $customer = null;

    /**
     * Authenticate customer.
     *
     * @param  string  $guard
     * @return void
     */
    protected function authenticateCustomer(string $guard = Customer::DEFAULT_AUTH_GUARD): void
    {
        $email = 'customer@service.com';

        $customer = Customer::whereEmail($email)->first() ?? Customer::factory()->create(compact('email'));

        $this->setCustomer($customer);

        $this->actingAs($customer, $guard);
    }

    /**
     * Authenticate customer with permission.
     *
     * @param  PermissionEnum  $permissionEnum
     * @param  string  $guard
     * @return void
     */
    protected function authenticateCustomerWithPermission(
        PermissionEnum $permissionEnum,
        string $guard = Customer::DEFAULT_AUTH_GUARD
    ): void {
        $email = 'customer-permission@service.com';

        /** @var Customer $customer */
        $customer = Customer::whereEmail($email)->first() ??
            Customer::factory()->create([
                'email' => $email,
            ]);

        $permission = Permission::whereName($permissionEnum->value)->first() ??
            Permission::factory()->create([
                'name' => $permissionEnum->value,
                'guard_name' => $guard,
            ]);

        $customer->permissions()->syncWithoutDetaching($permission);

        $this->setCustomer($customer);

        $this->actingAs($customer, $guard);
    }

    /**
     * Get customer.
     *
     * @return Customer|null
     */
    protected function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    /**
     * Set customer.
     *
     * @param  Customer  $customer
     * @return $this
     */
    protected function setCustomer(Customer $customer): static
    {
        $this->customer = $customer;

        return $this;
    }
}
