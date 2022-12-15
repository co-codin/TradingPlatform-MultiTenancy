<?php

namespace Tests\Feature\Modules\Customer\Admin;

use Modules\Customer\Enums\CustomerPermission;
use Modules\Customer\Models\Customer;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

class ReadTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    /**
     * Test authorized user can get customer list.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_get_customer_list(): void
    {
        $this->authenticateWithPermission(CustomerPermission::fromValue(CustomerPermission::VIEW_CUSTOMERS));

        $this->brand->makeCurrent();

        $customers = $this->brand->execute(function () {
            return Customer::factory()->make();
        });

        $customers->save();

        $response = $this->getJson(route('admin.customers.index'));

        $response->assertOk();

        $response->assertJson([
            'data' => [$customers->toArray()],
        ]);
    }

    /**
     * Test unauthorized user cant get customer list.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_cant_get_customer_list(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $customers = $this->brand->execute(function () {
            return Customer::factory()->make();
        });

        $customers->save();

        $response = $this->getJson(route('admin.customers.index'));

        $response->assertForbidden();
    }

    /**
     * Test unauthorized user get customer list.
     *
     * @return void
     *
     * @test
     */
    public function unauthorized_user_get_customer_list(): void
    {
        $this->brand->makeCurrent();

        $customers = $this->brand->execute(function () {
            return Customer::factory()->make();
        });

        $customers->save();

        $response = $this->getJson(route('admin.customers.index'));

        $response->assertUnauthorized();
    }

    /**
     * Test authorized user can get customer by ID.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_get_customer(): void
    {
        $this->authenticateWithPermission(CustomerPermission::fromValue(CustomerPermission::VIEW_CUSTOMERS));

        $this->brand->makeCurrent();

        $customers = $this->brand->execute(function () {
            return Customer::factory()->make();
        });

        $customers->save();

        $response = $this->getJson(route('admin.customers.show', ['customer' => $customers->id]));

        $response->assertOk();

        $response->assertJson(['data' => $customers->toArray()]);
    }

    /**
     * Test authorized user can get customer by ID.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_cant_get_customer(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $customers = $this->brand->execute(function () {
            return Customer::factory()->make();
        });

        $customers->save();

        $response = $this->getJson(route('admin.customers.show', ['customer' => $customers->id]));

        $response->assertForbidden();
    }

    /**
     * Test authorized user can get not found customer by ID.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_get_not_found_customer(): void
    {
        $this->authenticateWithPermission(CustomerPermission::fromValue(CustomerPermission::VIEW_CUSTOMERS));

        $this->brand->makeCurrent();

        $customerId = Customer::orderByDesc('id')->first()?->id + 1 ?? 1;

        $response = $this->getJson(route('admin.customers.show', ['customer' => $customerId]));

        $response->assertNotFound();
    }

    /**
     * Test unauthorized user can get customer by ID.
     *
     * @return void
     *
     * @test
     */
    public function unauthorized_user_can_get_customer(): void
    {
        $this->brand->makeCurrent();

        $customers = $this->brand->execute(function () {
            return Customer::factory()->make();
        });

        $customers->save();

        $response = $this->getJson(route('admin.customers.show', ['customer' => $customers->id]));

        $response->assertUnauthorized();
    }
}
