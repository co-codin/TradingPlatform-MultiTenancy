<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Customer;

use Illuminate\Support\Facades\Hash;
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
            'password' => 'password',
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
            'password' => 'password',
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
        $customer = $this->getCustomer();
        $response = $this->post(route('customer.token-auth.login'), [
            'email' => $customer->email,
            'password' => 'random',
        ]);

        $response->assertUnprocessable();
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->makeCurrentTenantAndSetHeader();
        $this->setCustomer(Customer::factory()->create([
            'password' => Hash::make('password'),
        ]));
    }
}