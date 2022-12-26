<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Transaction\Admin\Mt5Type;

use Modules\Transaction\Enums\TransactionsMt5TypePermission;
use Modules\Transaction\Models\TransactionsMt5Type;
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
        $this->authenticateWithPermission(
            TransactionsMt5TypePermission::fromValue(TransactionsMt5TypePermission::DELETE_TRANSACTION_MT5_TYPE)
        );

        $this->brand->makeCurrent();

        $transactionsMt5Type = TransactionsMt5Type::factory()->create();

        $response = $this->delete(route('admin.transaction-mt5-types.destroy', ['transaction_mt5_type' => $transactionsMt5Type]));

        $response->assertNoContent();
    }

    /**
     * @test
     */
    public function cant_delete(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $transactionsMt5Type = TransactionsMt5Type::factory()->create();

        $response = $this->delete(route('admin.transaction-mt5-types.destroy', ['transaction_mt5_type' => $transactionsMt5Type]));

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_found(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $transactionsMt5TypeId = TransactionsMt5Type::orderByDesc('id')->first()?->id + 1 ?? 1;

        $response = $this->delete(route('admin.transaction-mt5-types.destroy', ['transaction_mt5_type' => $transactionsMt5TypeId]));

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function unauthorized(): void
    {
        $this->brand->makeCurrent();

        $transactionsMt5Type = TransactionsMt5Type::factory()->create();

        $response = $this->delete(route('admin.transaction-mt5-types.destroy', ['transaction_mt5_type' => $transactionsMt5Type]));

        $response->assertUnauthorized();
    }
}
