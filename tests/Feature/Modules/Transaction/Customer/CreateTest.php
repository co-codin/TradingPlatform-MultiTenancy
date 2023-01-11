<?php

declare(strict_types=1);

namespace tests\Feature\Modules\Transaction\Customer;

use Modules\Transaction\Models\Transaction;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class CreateTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    /**
     * @test
     */
    public function can_create(): void
    {
        $this->authenticateCustomer();

        $this->brand->makeCurrent();

        $data = Transaction::factory()->make()->toArray();

        $response = $this->post(route('customer.transactions.store'), $data);

        $response->assertCreated();
        $response->assertJson(['data' => $data]);
    }

    /**
     * @test
     */
    public function not_unauthorized(): void
    {
        $response = $this->post(route('customer.transactions.store'));

        $response->assertUnauthorized();
    }
}
