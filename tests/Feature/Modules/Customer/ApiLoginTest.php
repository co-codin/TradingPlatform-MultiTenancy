<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Customer;

use Modules\Customer\Models\Customer;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasCustomerAuth;

final class ApiLoginTest extends BrandTestCase
{
    use TenantAware;
    use HasCustomerAuth;

    /**
     * @test
     */
    public function success(): void
    {
        $customer = $this->getCustomer();
        $response = $this->post(route('customer.token-auth.login'), [
            'email' => $customer->email,
            'password' => 'Password%',
        ]);

        $response->assertOk();

        $response->assertJsonStructure([
            'token',
            'expired_at',
        ]);
        $this->assertNotNull($customer->tokens()->where('name', 'api')->first());
        $this->actingAs($customer, Customer::DEFAULT_AUTH_GUARD)->withToken($response->json('token'));
        $this->assertAuthenticatedAs($customer, Customer::DEFAULT_AUTH_GUARD);
    }

    /**
     * @test
     * @depends success
     */
    public function remember_success(): void
    {
        $customer = $this->getCustomer();
        $response = $this->post(route('customer.token-auth.login'), [
            'email' => $customer->email,
            'password' => 'Password%',
            'remember_me' => true,
        ]);

        $response->assertOk();

        $response->assertJsonStructure([
            'token',
            'expired_at',
        ]);
        $this->assertNotNull($customer->tokens()->where('name', 'api')->first());
        $this->actingAs($customer, Customer::DEFAULT_AUTH_GUARD)->withToken($response->json('token'));
        $this->assertAuthenticatedAs($customer, Customer::DEFAULT_AUTH_GUARD);
    }

    /**
     * @test
     */
    public function failed(): void
    {
        $this->brand->makeCurrent();

        $customer = $this->getCustomer();
        $response = $this->post(route('customer.token-auth.login'), [
            'email' => $customer->email,
            'password' => 'Password%',
        ]);

        $response->assertUnprocessable();
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->brand->makeCurrent();

        $customer = $this->brand->execute(function () {
            return Customer::factory()->make();
        });

        $customer->save();

        $this->setCustomer($customer);
    }
}
