<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Customer;

use Modules\Customer\Enums\CustomerPermission;
use Modules\Customer\Models\Customer;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class ImpersonateTokenTest extends BrandTestCase
{
    use HasAuth;

    private Customer $targetCustomer;

    /**
     * @test
     */
    public function can_impersonate(): void
    {
        $this->authenticateWithPermission(CustomerPermission::fromValue(CustomerPermission::IMPERSONATE_CUSTOMERS));
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

        $this->makeCurrentTenantAndSetHeader();

        $this->targetCustomer = Customer::factory()->create();
    }
}
