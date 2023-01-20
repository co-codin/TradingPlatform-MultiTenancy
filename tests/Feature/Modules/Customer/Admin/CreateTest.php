<?php

namespace Tests\Feature\Modules\Customer\Admin;

use Illuminate\Support\Arr;
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

        $customers = $this->brand->execute(function () {
            return Customer::factory()->make();
        });

        $data = Arr::except($customers->toArray(), [
            'conversion_sale_status_id',
            'retention_sale_status_id',
            'affiliate_user_id',
            'conversion_user_id',
            'retention_user_id',
            'compliance_user_id',
            'support_user_id',
            'conversion_manager_user_id',
            'retention_manager_user_id',
            'first_conversion_user_id',
            'first_retention_user_id',
        ]);

        $response = $this->postJson(route('admin.customers.store'), array_merge($data, ['password' => self::$basePassword]));

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
