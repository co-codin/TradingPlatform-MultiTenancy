<?php

namespace Tests\Feature\Modules\Customer\Admin;

use Modules\Customer\Enums\CustomerPermission;
use Modules\Customer\Models\Customer;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

class DeleteTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    /**
     * Test authorized user can delete customer.
     *
     * @return void
     *
     * @test
     */
    final public function authorized_user_can_delete_customer(): void
    {
        $this->authenticateWithPermission(CustomerPermission::fromValue(CustomerPermission::DELETE_CUSTOMERS));

        $this->brand->makeCurrent();

        $customers = $this->brand->execute(function () {
            return Customer::factory()->make();
        });

        $customers->save();

        $response = $this->deleteJson(route('admin.customers.destroy', ['customer' => $customers->id]));

        $response->assertNoContent();
    }

    /**
     * Test authorized user can`t delete customer.
     *
     * @return void
     *
     * @test
     */
    final public function authorized_user_cant_delete_customer(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $customers = $this->brand->execute(function () {
            return Customer::factory()->make();
        });

        $customers->save();

        $response = $this->deleteJson(route('admin.customers.destroy', ['customer' => $customers->id]));

        $response->assertForbidden();
    }

    /**
     * Test authorized user can delete not found customer.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_delete_not_found_customer(): void
    {
        $this->authenticateWithPermission(CustomerPermission::fromValue(CustomerPermission::DELETE_CUSTOMERS));

        $this->brand->makeCurrent();

        $customer = Customer::orderByDesc('id')->first()?->id + 1 ?? 1;
        $response = $this->delete(route('admin.customers.destroy', ['customer' => $customer]));

        $response->assertNotFound();
    }

    /**
     * Test unauthorized user can`t delete customer.
     *
     * @return void
     *
     * @test
     */
    final public function unauthorized(): void
    {
        $this->brand->makeCurrent();

        $customers = $this->brand->execute(function () {
            return Customer::factory()->make();
        });

        $customers->save();

        $response = $this->patchJson(route('admin.customers.destroy', ['customer' => $customers->id]));

        $response->assertUnauthorized();
    }
}
