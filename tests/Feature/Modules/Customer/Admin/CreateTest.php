<?php

namespace Tests\Feature\Modules\Customer\Admin;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Customer\Enums\CustomerPermission;
use Modules\Customer\Models\Customer;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use DatabaseTransactions, CustomerAdminTrait;
    /**
     * Test authorized user can create customer.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_create_customer(): void
    {
        $this->authenticateWithPermission(CustomerPermission::fromValue(CustomerPermission::CREATE_CUSTOMERS));

        $data = Customer::factory()->make()->toArray();

        $response = $this->postJson(route('admin.customers.store'), array_merge($data, ['password' => 'password']));

        $response->assertCreated();

        $response->assertJson(['data' => $data]);
    }

    /**
     * Test authorized user can`t create customer.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_cant_create_customer(): void
    {
        $this->authenticateUser();

        $data = Customer::factory()->make()->toArray();

        $response = $this->postJson(route('admin.customers.store'), array_merge($data, ['password' => 'password']));

        $response->assertForbidden();
    }

    /**
     * Test unauthorized user can`t create customer.
     *
     * @return void
     *
     * @test
     */
    public function unauthorized(): void
    {
        $data = Customer::factory()->make()->toArray();

        $response = $this->postJson(route('admin.customers.store'), $data);

        $response->assertUnauthorized();
    }
}
