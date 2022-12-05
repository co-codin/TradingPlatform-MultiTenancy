<?php

namespace Tests\Feature\Modules\Customer\Admin;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Modules\Customer\Enums\CustomerPermission;
use Modules\Customer\Models\Customer;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use DatabaseTransactions, CustomerAdminTrait;

    /**
     * Test authorized user can update customer with empty password field.
     *
     * @return void
     *
     * @test
     */
    final public function authorized_user_can_update_customer_with_empty_password_field(): void
    {
        $this->authenticateWithPermission(CustomerPermission::fromValue(CustomerPermission::EDIT_CUSTOMERS));

        $customer = Customer::factory()->create();
        $data = Customer::factory()->make()->toArray();

        $response = $this->patchJson(route('admin.customers.update', ['customer' => $customer->id]), $data);

        $response->assertOk();

        $response->assertJson([
            'data' => $data,
        ]);
    }

    /**
     * Test authorized user can update customer with password field.
     *
     * @return void
     *
     * @test
     */
    final public function authorized_user_can_update_customer_with_password_field(): void
    {
        $this->authenticateWithPermission(CustomerPermission::fromValue(CustomerPermission::EDIT_CUSTOMERS));

        $customer = Customer::factory()->create();
        $data = Customer::factory()->make()->toArray();

        $response = $this->patchJson(route('admin.customers.update', ['customer' => $customer->id]), array_merge($data, ['password' => Hash::make('password')]));

        $response->assertOk();

        $response->assertJson([
            'data' => $data,
        ]);
    }

    /**
     * Test authorized user can`t update customer.
     *
     * @return void
     *
     * @test
     */
    final public function authorized_user_cant_update_customer(): void
    {
        $this->authenticateUser();

        $customer = Customer::factory()->create();
        $data = Customer::factory()->make()->toArray();

        $response = $this->patchJson(route('admin.customers.update', ['customer' => $customer->id]), $data);

        $response->assertForbidden();
    }
    /**
     * Test authorized user can update not found customer.
     *
     * @return void
     *
     * @test
     */
    final public function authorized_user_can_update_not_found_customer(): void
    {
        $this->authenticateWithPermission(CustomerPermission::fromValue(CustomerPermission::EDIT_CUSTOMERS));

        $customerId = Customer::orderByDesc('id')->first()?->id + 1 ?? 1;
        $data = Customer::factory()->make()->toArray();

        $response = $this->patchJson(route('admin.customers.update', ['customer' => $customerId]), $data);

        $response->assertNotFound();
    }
    /**
     * Test unauthorized user can update customer.
     *
     * @return void
     *
     * @test
     */
    final public function unauthorized(): void
    {
        $customer = Customer::factory()->create();
        $data = Customer::factory()->make()->toArray();

        $response = $this->patchJson(route('admin.customers.update', ['customer' => $customer->id]), $data);

        $response->assertUnauthorized();
    }
}
