<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Customer;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Customer\Models\Customer;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasCustomerAuth;

final class LoginTest extends BrandTestCase
{
    use TenantAware;
    use HasCustomerAuth;

    /**
     * @test
     */
    public function success(): void
    {
        $customer = $this->getCustomer();
        $response = $this->post(route('customer.auth.login'), [
            'email' => $customer->email,
            'password' => 'password',
        ]);

        $response->assertNoContent();
        $this->assertAuthenticatedAs($customer, Customer::DEFAULT_AUTH_GUARD);
    }

    /**
     * @test
     * @depends success
     */
    public function remember_success(): void
    {
        $customer = $this->getCustomer();
        $response = $this->post(route('customer.auth.login'), [
            'email' => $customer->email,
            'password' => 'password',
            'remember_me' => true,
        ]);

        $response->assertNoContent();
        $this->assertAuthenticatedAs($customer, Customer::DEFAULT_AUTH_GUARD);

        $response->assertCookie(Auth::guard(Customer::DEFAULT_AUTH_GUARD)->getRecallerName(), vsprintf('%s|%s|%s', [
            $customer->id,
            $customer->getRememberToken(),
            $customer->password,
        ]));
    }

    /**
     * @test
     */
    public function login_failed(): void
    {
        $customer = $this->getCustomer();
        $response = $this->post(route('customer.auth.login'), [
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
