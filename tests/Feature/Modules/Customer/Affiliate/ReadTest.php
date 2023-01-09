<?php

namespace Tests\Feature\Modules\Customer\Affiliate;

use Modules\Customer\Enums\CustomerPermission;
use Modules\Customer\Models\Customer;
use Modules\Role\Enums\DefaultRole;
use Modules\Role\Models\Role;
use Modules\User\Models\User;
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
    public function affiliate_user_can_get_customer_list(): void
    {
        $this->authenticateWithPermission(CustomerPermission::fromValue(CustomerPermission::VIEW_CUSTOMERS));

        $this->brand->makeCurrent();

        $customers = $this->brand->execute(function () {
            return Customer::factory()->make();
        });

        $customers->save();

        $this->user->assignRole(
            Role::where('name', DefaultRole::AFFILIATE)->first()
            ?? Role::factory()->create([
                'name' => DefaultRole::AFFILIATE,
            ])
        );

        $response = $this->getJson(route('affiliate.customers.index'));

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
    public function affiliate_user_cant_get_customer_list(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $customers = $this->brand->execute(function () {
            return Customer::factory()->make();
        });

        $customers->save();

        $this->user->assignRole(
            Role::where('name', DefaultRole::AFFILIATE)->first()
            ?? Role::factory()->create([
                'name' => DefaultRole::AFFILIATE,
            ])
        );

        $response = $this->getJson(route('affiliate.customers.index'));

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

        $response = $this->getJson(route('affiliate.customers.index'));

        $response->assertUnauthorized();
    }

    /**
     * Test authorized user can get customer by ID.
     *
     * @return void
     *
     * @test
     */
    public function affiliate_user_can_get_customer(): void
    {
        $this->authenticateWithPermission(CustomerPermission::fromValue(CustomerPermission::VIEW_CUSTOMERS));

        $this->brand->makeCurrent();

        $customer = $this->brand->execute(function () {
            return Customer::factory()->make();
        });

        $customer->save();

        $this->user->assignRole(
            Role::where('name', DefaultRole::AFFILIATE)->first()
            ?? Role::factory()->create([
                'name' => DefaultRole::AFFILIATE,
            ])
        );

        $response = $this->getJson(route('affiliate.customers.show', ['customer' => $customer->id]));

        $response->assertOk();

        $response->assertJson(['data' => $customer->toArray()]);
    }

    /**
     * Test authorized user can get customer by ID.
     *
     * @return void
     *
     * @test
     */
    public function affiliate_user_cant_get_customer(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $customer = $this->brand->execute(function () {
            return Customer::factory()->make();
        });

        $customer->save();

        $this->user->assignRole(
            Role::where('name', DefaultRole::AFFILIATE)->first()
            ?? Role::factory()->create([
                'name' => DefaultRole::AFFILIATE,
            ])
        );

        $response = $this->getJson(route('affiliate.customers.show', ['customer' => $customer->id]));

        $response->assertForbidden();
    }

    /**
     * Test authorized user can get not found customer by ID.
     *
     * @return void
     *
     * @test
     */
    public function affiliate_user_can_get_not_found_customer(): void
    {
        $this->authenticateWithPermission(CustomerPermission::fromValue(CustomerPermission::VIEW_CUSTOMERS));

        $this->brand->makeCurrent();

        $customerId = Customer::orderByDesc('id')->first()?->id + 1 ?? 1;

        $this->user->assignRole(
            Role::where('name', DefaultRole::AFFILIATE)->first()
            ?? Role::factory()->create([
                'name' => DefaultRole::AFFILIATE,
            ])
        );

        $response = $this->getJson(route('affiliate.customers.show', ['customer' => $customerId]));

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

        $customer = $this->brand->execute(function () {
            return Customer::factory()->make();
        });

        $customer->save();

        $response = $this->getJson(route('affiliate.customers.show', ['customer' => $customer->id]));

        $response->assertUnauthorized();
    }
}
