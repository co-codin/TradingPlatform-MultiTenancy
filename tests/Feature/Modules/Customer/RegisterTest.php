<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Customer;

use Modules\Customer\Models\Customer;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;

final class RegisterTest extends BrandTestCase
{
    use TenantAware;

    /**
     * @test
     */
    public function success(): void
    {
        $this->brand->makeCurrent();
        $response = $this->post(route('customer.auth.register'), Customer::factory()->raw([
            'password' => 'password',
            'password_confirmation' => 'password',
        ]));

        $response->assertNoContent();
        $this->assertAuthenticated('web-customer');
    }
}
