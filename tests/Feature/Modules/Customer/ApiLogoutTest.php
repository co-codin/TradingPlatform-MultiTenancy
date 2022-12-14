<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Customer;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Hash;
use Modules\Customer\Models\Customer;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasCustomerAuth;

final class ApiLogoutTest extends BrandTestCase
{
    use TenantAware;
    use HasCustomerAuth;

    /**
     * @test
     */
    public function success(): void
    {
        $customer = Customer::factory()->create([
            'password' => Hash::make('password'),
        ]);
        $expiredAt = CarbonImmutable::now()->add(config('auth.api_token_expires_in'));
        $token = $customer->createToken('api', expiresAt: $expiredAt);

        $response = $this->withToken($token->plainTextToken)->post(route('customer.token-auth.logout'));
        $response->assertNoContent();

        $this->assertModelMissing($token->accessToken);
    }

    /**
     * @test
     */
    public function failed(): void
    {
        $response = $this->post(route('customer.token-auth.logout'));

        $response->assertUnauthorized();
        $this->assertGuest();
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->makeCurrentTenantAndSetHeader();
    }
}
