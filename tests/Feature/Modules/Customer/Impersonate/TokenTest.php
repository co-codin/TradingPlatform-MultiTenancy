<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Customer\Impersonate;

use Modules\Customer\Enums\CustomerPermission;
use Modules\Customer\Models\Customer;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class TokenTest extends BrandTestCase
{
    use HasAuth;
    use TenantAware;

    private Customer $targetCustomer;

    /**
     * @test
     */
    public function can_impersonate(): void
    {
        $this->authenticateWithPermission(CustomerPermission::fromValue(CustomerPermission::IMPERSONATE_CUSTOMERS));

        $this->brand->makeCurrent();
        $customer = $this->targetCustomer;
        $response = $this->post(route('admin.customers.impersonate.token', ['id' => $customer->id]));

        $response->assertOk();

        $response->assertJsonStructure([
            'impersonator',
            'impersonator_token',
            'target_customer',
            'target_token',
            'target_token_expired_at',
        ]);
        $this->assertNotNull($customer->tokens()->where('name', 'api')->first());
        $this->actingAs($customer)->withToken($response->json('target_token'));
        $this->assertAuthenticatedAs($customer);
    }

    /**
     * @test
     */
    public function cant_impersonate(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();
        $response = $this->post(route('admin.customers.impersonate.token', ['id' => $this->targetCustomer->id]));

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function unauthorized(): void
    {
        $response = $this->post(route('admin.customers.impersonate.token', ['id' => $this->targetCustomer->id]));

        $response->assertUnauthorized();
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->brand->makeCurrent();
        $this->targetCustomer = $this->brand->execute(function () {
            return Customer::factory()->make();
        });

        $this->targetCustomer->save();
    }
}
