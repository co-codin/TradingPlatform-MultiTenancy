<?php

namespace Tests\Feature\Modules\Customer\Admin;

use Modules\Customer\Enums\CustomerPermission;
use Modules\Customer\Models\Customer;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

class CreateTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

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

        $this->brand->makeCurrent();

        $data = Customer::factory()->make()->toArray();
        $this->brand->makeCurrent();

        $response = $this->postJson(route('admin.customers.store'), array_merge($data, ['password' => self::$basePassword]));

        unset($data['conversion_sale_status_id']);
        unset($data['retention_sale_status_id']);
        unset($data['affiliate_user_id']);
        unset($data['conversion_user_id']);
        unset($data['retention_user_id']);
        unset($data['compliance_user_id']);
        unset($data['support_user_id']);
        unset($data['conversion_manager_user_id']);
        unset($data['retention_manager_user_id']);
        unset($data['first_conversion_user_id']);
        unset($data['first_retention_user_id']);

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

        $this->brand->makeCurrent();

        $data = Customer::factory()->make()->toArray();
        $this->brand->makeCurrent();

        $response = $this->postJson(route('admin.customers.store'), array_merge($data, ['password' => self::$basePassword]));

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
        $this->brand->makeCurrent();

        $data = Customer::factory()->make()->toArray();
        $this->brand->makeCurrent();

        $response = $this->postJson(route('admin.customers.store'), $data);

        $response->assertUnauthorized();
    }
}
