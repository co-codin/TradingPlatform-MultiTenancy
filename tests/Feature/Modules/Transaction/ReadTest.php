<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Transaction;

use Modules\Currency\Models\Currency;
use Modules\Transaction\Enums\TransactionPermission;
use Modules\Transaction\Models\Transaction;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class ReadTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    /**
     * @test
     */
    public function can_view_any(): void
    {
        $this->authenticateCustomerWithPermission(
            TransactionPermission::fromValue(TransactionPermission::VIEW_TRANSACTIONS)
        );

        $this->brand->makeCurrent();

        $transaction = Transaction::factory(10)->create();

        $response = $this->get(route('api.transactions.index'));

        $response->assertOk();

        $response->assertJson([
            'data' => $transaction->only('id')->toArray(),
        ]);
    }

    /**
     * @test
     */
    public function can_not_view_any(): void
    {
        $this->authenticateCustomer();

        $this->brand->makeCurrent();

        $response = $this->get(route('api.transactions.index'));

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function can_view(): void
    {
        $this->authenticateCustomerWithPermission(
            TransactionPermission::fromValue(TransactionPermission::VIEW_TRANSACTIONS)
        );

        $this->brand->makeCurrent();

        $transaction = Transaction::factory()->create();

        $response = $this->get(route('api.transactions.show', ['transaction' => $transaction]));

        $response->assertOk();
        $response->assertJson(['data' => $transaction->toArray()]);
    }

    /**
     * @test
     */
    public function can_not_view(): void
    {
        $this->authenticateCustomer();

        $this->brand->makeCurrent();
        $transaction = Transaction::factory()->create();

        $response = $this->get(route('api.transactions.show', ['transaction' => $transaction]));

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_found(): void
    {
        $this->authenticateCustomer();
        $transactionId = Transaction::query()->orderByDesc('id')->first()?->id + 1 ?? 1;

        $response = $this->get(route('api.transactions.show', ['transaction' => $transactionId]));

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function unauthorized_view_any(): void
    {
        $this->authenticateUser();

        $response = $this->get(route('api.transactions.index'));

        $response->assertUnauthorized();
    }

    /**
     * @test
     */
    public function not_unauthorized_view(): void
    {
        $this->brand->makeCurrent();
        $transaction = Transaction::factory()->create();

        $response = $this->get(route('api.transactions.show', ['transaction' => $transaction]));

        $response->assertUnauthorized();
    }
}
