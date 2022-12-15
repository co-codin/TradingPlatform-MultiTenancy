<?php

namespace Tests\Feature\Modules\Customer\Admin;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Customer\Enums\CustomerPermission;
use Modules\Customer\Models\Customer;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\TestCase;
use Tests\Traits\HasAuth;

class ReadTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;
    use WithFaker;
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

        $customers = Customer::factory()->create();

        $response = $this->getJson(route('admin.customers.index'));

        $response->assertOk();

//        $response->assertJson([
//            'data' => [$customers->toArray()],
//        ]);
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

        Customer::factory()->create();

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
        Customer::factory()->create();

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

        $customers = Customer::factory()->create();

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

        $customers = Customer::factory()->create();

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
        $customer = Customer::factory()->create();

        $response = $this->getJson(route('admin.customers.show', ['customer' => $customer->id]));

        $response->assertUnauthorized();
    }
}
