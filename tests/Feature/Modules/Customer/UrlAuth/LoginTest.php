<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Customer\UrlAuth;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
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

        $key = Str::random();

        Cache::put("url-auth:{$key}", [
            'customerId' => $customer->id,
            'brandId' => $this->brand->id,
        ], now()->addMinutes(30));

        $response = $this->get(route('customer.url-auth.login', compact('key')));

        $response->assertNoContent();
        $this->assertAuthenticatedAs($customer, Customer::DEFAULT_AUTH_GUARD);
    }

    /**
     * @test
     */
    public function login_not_found(): void
    {
        $response = $this->get(route('customer.url-auth.login'), [
            'key' => Str::random(),
        ]);

        $response->assertNotFound();
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
