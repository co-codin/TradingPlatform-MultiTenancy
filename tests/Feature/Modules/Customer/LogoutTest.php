<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Customer;

use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasCustomerAuth;

final class LogoutTest extends BrandTestCase
{
    use TenantAware;
    use HasCustomerAuth;

    /**
     * @test
     */
    public function success(): void
    {
        $this->makeCurrentTenantAndSetHeader();
        $this->authenticateCustomer();

        $response = $this->post(route('customer.auth.logout'));

        $response->assertNoContent();
        $this->assertGuest();
    }

    /**
     * @test
     */
    public function failed(): void
    {
        $this->makeCurrentTenantAndSetHeader();
        $response = $this->post(route('customer.auth.logout'));

        $response->assertUnauthorized();
        $this->assertGuest();
    }
}
