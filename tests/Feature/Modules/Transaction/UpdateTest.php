<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Transaction;

use Modules\Currency\Models\Currency;
use Modules\Transaction\Enums\TransactionPermission;
use Modules\Transaction\Models\Transaction;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class UpdateTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    /**
     * @test
     */
    public function can_update(): void
    {
        $this->authenticateCustomerWithPermission(
            TransactionPermission::fromValue(TransactionPermission::EDIT_TRANSACTIONS)
        );

        $this->brand->makeCurrent();

        $transaction = Transaction::factory()->create();
        $transactionData = Transaction::factory()->make()->toArray();

        $this->brand->makeCurrent();

        $response = $this->patch(route('api.transactions.update', ['transaction' => $transaction]), $transactionData);

        $response->assertOk();
        $response->assertJson(['data' => $transactionData]);
    }

    /**
     * @test
     */
    public function can_not_update(): void
    {
        $this->authenticateCustomer();

        $this->brand->makeCurrent();

        $transaction = Transaction::factory()->create();
        $data = Transaction::factory()->make();

        $this->brand->makeCurrent();

        $response = $this->patch(
            route('api.transactions.update', ['transaction' => $transaction]), $data->toArray()
        );

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_found(): void
    {
        $this->authenticateCustomer();

        $this->brand->makeCurrent();

        $transactionId = Transaction::orderByDesc('id')->first()?->id + 1 ?? 1;
        $data = Transaction::factory()->make();

        $this->brand->makeCurrent();

        $response = $this->patch(route('api.transactions.update', ['transaction' => $transactionId]),
            $data->toArray());

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function unauthorized(): void
    {
        $this->brand->makeCurrent();

        $transaction = Transaction::factory()->create();

        $response = $this->patch(route('api.transactions.update', ['transaction' => $transaction]));

        $response->assertUnauthorized();
    }
}
