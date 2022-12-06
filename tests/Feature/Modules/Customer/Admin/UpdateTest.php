<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Customer\Admin;

use Modules\Customer\Enums\CustomerPermission;
use Modules\Customer\Models\Customer;
use Tests\TestCase;

final class UpdateTest extends TestCase
{
    /**
     * Test authorized user can update customer.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_update_customer(): void
    {
        $this->authenticateWithPermission(CustomerPermission::fromValue(CustomerPermission::EDIT_CUSTOMERS));

        $customer = Customer::factory()->create();
        $data = Customer::factory()->make();

        $response = $this->patchJson(route('admin.customers.update', ['customer' => $customer->id]), $data->toArray());
        $response->assertOk();

        $response->assertJsonStructure([
            'data' => array_keys($data->toArray()),
        ]);
    }

    /**
     * Test unauthorized user can`t update customer.
     *
     * @return void
     *
     * @test
     */
    public function unauthorized_user_cant_update_customer(): void
    {
        $customer = Customer::factory()->create();
        $data = Customer::factory()->make();

        $response = $this->patchJson(route('admin.customers.update', ['customer' => $customer->id]), $data->toArray());

        $response->assertUnauthorized();
    }

    /**
     * Test authorized user cant update not existed customer.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_update_not_existed_customer(): void
    {
        $this->authenticateWithPermission(CustomerPermission::fromValue(CustomerPermission::EDIT_CUSTOMERS));

        $customerId = Customer::orderByDesc('id')->first()?->id ?? 1;
        $data = Customer::factory()->make();

        $response = $this->patchJson(route('admin.customers.update', ['customer' => $customerId]), $data->toArray());

        $response->assertNotFound();
    }

    /**
     * Test cant update existed first conversion user id.
     *
     * @return void
     *
     * @test
     */
    public function cant_update_existed_first_conversion_user_id(): void
    {
        $this->authenticateWithPermission(CustomerPermission::fromValue(CustomerPermission::EDIT_CUSTOMERS));

        $customer = Customer::factory()->create();
        $data = Customer::factory()->make();

        $response = $this->patchJson(route('admin.customers.update', ['customer' => $customer->id]), $data->toArray());

        $response->assertOk();

        $response->assertJson([
            'data' => [
                'conversion_user_id' => $data->conversion_user_id,
                'first_conversion_user_id' => $customer->first_conversion_user_id,
            ],
        ]);
    }

    /**
     * Test cant update existed first retention user id.
     *
     * @return void
     *
     * @test
     */
    public function cant_update_existed_first_retention_user_id(): void
    {
        $this->authenticateWithPermission(CustomerPermission::fromValue(CustomerPermission::EDIT_CUSTOMERS));

        $customer = Customer::factory()->create();
        $data = Customer::factory()->make();

        $response = $this->patchJson(route('admin.customers.update', ['customer' => $customer->id]), $data->toArray());

        $response->assertOk();

        $response->assertJson([
            'data' => [
                'retention_user_id' => $data->retention_user_id,
                'first_retention_user_id' => $customer->first_retention_user_id,
            ],
        ]);
    }
}
