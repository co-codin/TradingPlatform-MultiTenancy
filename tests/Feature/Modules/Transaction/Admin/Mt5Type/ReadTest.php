<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Transaction\Admin\Mt5Type;

use Modules\Transaction\Enums\TransactionsMt5TypePermission;
use Modules\Transaction\Models\TransactionsMt5Type;
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
        $this->authenticateWithPermission(
            TransactionsMt5TypePermission::fromValue(TransactionsMt5TypePermission::VIEW_TRANSACTION_MT5_TYPE)
        );

        $this->brand->makeCurrent();

        $transactionsMt5Type = TransactionsMt5Type::factory(10)->create();

        $response = $this->get(route('admin.transaction-mt5-types.index'));

        $response->assertOk();

        $response->assertJson([
            'data' => $transactionsMt5Type->only('id')->toArray(),
        ]);
    }

    /**
     * @test
     */
    public function can_not_view_any(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $response = $this->get(route('admin.transaction-mt5-types.index'));

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function can_view(): void
    {
        $this->authenticateWithPermission(
            TransactionsMt5TypePermission::fromValue(TransactionsMt5TypePermission::VIEW_TRANSACTION_MT5_TYPE)
        );

        $this->brand->makeCurrent();

        $transactionsMt5Type = TransactionsMt5Type::factory()->create();

        $response = $this->get(route('admin.transaction-mt5-types.show', ['transaction_mt5_type' => $transactionsMt5Type]));

        $response->assertOk();
        $response->assertJson(['data' => $transactionsMt5Type->toArray()]);
    }

    /**
     * @test
     */
    public function can_not_view(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();
        $transactionsMt5Type = TransactionsMt5Type::factory()->create();

        $response = $this->get(route('admin.transaction-mt5-types.show', ['transaction_mt5_type' => $transactionsMt5Type]));

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_found(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();
        $transactionsMt5TypeId = TransactionsMt5Type::query()->orderByDesc('id')->first()?->id + 1 ?? 1;

        $response = $this->get(route('admin.transaction-mt5-types.show', ['transaction_mt5_type' => $transactionsMt5TypeId]));

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function unauthorized_view_any(): void
    {
        $response = $this->get(route('admin.transaction-mt5-types.index'));

        $response->assertUnauthorized();
    }

    /**
     * @test
     */
    public function not_unauthorized_view(): void
    {
        $this->brand->makeCurrent();
        $transactionsMt5Type = TransactionsMt5Type::factory()->create();

        $response = $this->get(route('admin.transaction-mt5-types.show', ['transaction_mt5_type' => $transactionsMt5Type]));

        $response->assertUnauthorized();
    }
}
