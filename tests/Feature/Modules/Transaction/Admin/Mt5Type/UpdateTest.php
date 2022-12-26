<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Transaction\Admin\Mt5Type;

use Modules\Transaction\Enums\TransactionsMt5TypePermission;
use Modules\Transaction\Models\TransactionsMt5Type;
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
        $this->authenticateWithPermission(
            TransactionsMt5TypePermission::fromValue(TransactionsMt5TypePermission::EDIT_TRANSACTION_MT5_TYPE)
        );

        $this->brand->makeCurrent();

        $transactionsMt5Type = TransactionsMt5Type::factory()->create();
        $transactionsMt5TypeData = TransactionsMt5Type::factory()->make()->toArray();

        $this->brand->makeCurrent();

        $response = $this->patch(route('admin.transaction-mt5-types.update', ['transaction_mt5_type' => $transactionsMt5Type]), $transactionsMt5TypeData);

        $response->assertOk();
        $response->assertJson(['data' => $transactionsMt5TypeData]);
    }

    /**
     * @test
     */
    public function can_not_update(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $transactionsMt5Type = TransactionsMt5Type::factory()->create();
        $data = TransactionsMt5Type::factory()->make();

        $this->brand->makeCurrent();

        $response = $this->patch(
            route('admin.transaction-mt5-types.update', ['transaction_mt5_type' => $transactionsMt5Type]), $data->toArray()
        );

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
        $data = TransactionsMt5Type::factory()->make();

        $this->brand->makeCurrent();

        $response = $this->patch(route('admin.transaction-mt5-types.update', ['transaction_mt5_type' => $transactionsMt5TypeId]),
            $data->toArray());

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function unauthorized(): void
    {
        $this->brand->makeCurrent();
        $transactionsMt5Type = TransactionsMt5Type::factory()->create();

        $response = $this->patch(route('admin.transaction-mt5-types.update', ['transaction_mt5_type' => $transactionsMt5Type]));

        $response->assertUnauthorized();
    }
}
