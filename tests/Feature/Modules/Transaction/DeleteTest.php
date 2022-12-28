<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Transaction;

use Modules\Currency\Models\Currency;
use Modules\Transaction\Enums\TransactionPermission;
use Modules\Transaction\Models\Transaction;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class DeleteTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    /**
     * @test
     */
    public function can_delete(): void
    {
        $this->authenticateCustomerWithPermission(
            TransactionPermission::fromValue(TransactionPermission::DELETE_TRANSACTIONS)
        );

        $this->brand->makeCurrent();

        $transactions = Transaction::factory()->create();

        $response = $this->delete(route('api.transactions.destroy', ['transaction' => $transactions]));

        $response->assertNoContent();
    }

    /**
     * @test
     */
    public function cant_delete(): void
    {
        $this->authenticateCustomer();

        $this->brand->makeCurrent();

        $transactions = Transaction::factory()->create();

        $response = $this->delete(route('api.transactions.destroy', ['transaction' => $transactions]));

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_found(): void
    {
        $this->authenticateCustomer();

        $this->brand->makeCurrent();

        $transactionsId = Transaction::orderByDesc('id')->first()?->id + 1 ?? 1;

        $response = $this->delete(route('api.transactions.destroy', ['transaction' => $transactionsId]));

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function unauthorized(): void
    {
        $this->brand->makeCurrent();

        $transactions = Transaction::factory()->create();

        $response = $this->delete(route('api.transactions.destroy', ['transaction' => $transactions]));

        $response->assertUnauthorized();
    }
}
