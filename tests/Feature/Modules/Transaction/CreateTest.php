<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Transaction;

use Modules\Currency\Models\Currency;
use Modules\Transaction\Enums\TransactionPermission;
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
        $this->authenticateCustomerWithPermission(
            TransactionPermission::fromValue(TransactionPermission::CREATE_TRANSACTIONS)
        );

        $this->brand->makeCurrent();

        $data = Transaction::factory()->make()->toArray();

        $response = $this->post(route('api.transactions.store'), $data);

        $response->assertCreated();
        $response->assertJson(['data' => $data]);
    }

    /**
     * @test
     */
    public function can_not_create(): void
    {
        $this->authenticateCustomer();

        $this->brand->makeCurrent();

        $data = Transaction::factory()->make()->toArray();

        $response = $this->post(route('api.transactions.store'), $data);

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_unauthorized(): void
    {
        $response = $this->post(route('api.transactions.store'));

        $response->assertUnauthorized();
    }
}
