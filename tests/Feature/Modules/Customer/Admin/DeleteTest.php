<?php

namespace Tests\Feature\Modules\Customer\Admin;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Customer\Enums\CustomerPermission;
use Modules\Customer\Models\Customer;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use DatabaseTransactions;

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

        $customer = Customer::factory()->create();

        $response = $this->deleteJson(route('admin.customers.destroy', ['customer' => $customer->id]));

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

        $customer = Customer::factory()->create();

        $response = $this->deleteJson(route('admin.customers.destroy', ['customer' => $customer->id]));

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
        $customer = Customer::factory()->create();

        $response = $this->patchJson(route('admin.customers.destroy', ['customer' => $customer->id]));

        $response->assertUnauthorized();
    }
}
