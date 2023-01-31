<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Customer;

use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasCustomerAuth;

final class GetCurrentUserTest extends BrandTestCase
{
    use TenantAware;
    use HasCustomerAuth;

    /**
     * @test
     */
    public function success(): void
    {
        $this->authenticateCustomer();

        $this->brand->makeCurrent();

        $response = $this->get(route('customer.auth.me'));

        $response->assertOk();

        $response->assertJsonStructure([
            'data' => [
                'id',
                'gender',
                'email',
                'phone',
                'country',
            ],
        ]);
    }

    /**
     * @test
     */
    public function unauthorized(): void
    {
        $this->makeCurrentTenantAndSetHeader();
        $response = $this->get(route('customer.auth.me'));

        $response->assertUnauthorized();
    }
}
