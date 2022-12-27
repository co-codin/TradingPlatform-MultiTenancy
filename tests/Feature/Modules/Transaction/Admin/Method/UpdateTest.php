<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Transaction\Admin\Method;

use Modules\Transaction\Enums\TransactionsMethodPermission;
use Modules\Transaction\Models\TransactionsMethod;
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
            TransactionsMethodPermission::fromValue(TransactionsMethodPermission::EDIT_TRANSACTION_METHOD)
        );

        $this->brand->makeCurrent();

        $transactionsMethod = TransactionsMethod::factory()->create();
        $transactionsMethodData = TransactionsMethod::factory()->make()->toArray();

        $this->brand->makeCurrent();

        $response = $this->patch(route('admin.transaction-methods.update', ['transaction_method' => $transactionsMethod]), $transactionsMethodData);

        $response->assertOk();
        $response->assertJson(['data' => $transactionsMethodData]);
    }

    /**
     * @test
     */
    public function can_not_update(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $transactionsMethod = TransactionsMethod::factory()->create();
        $data = TransactionsMethod::factory()->make();

        $this->brand->makeCurrent();

        $response = $this->patch(
            route('admin.transaction-methods.update', ['transaction_method' => $transactionsMethod]), $data->toArray()
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

        $transactionsMethodId = TransactionsMethod::orderByDesc('id')->first()?->id + 1 ?? 1;
        $data = TransactionsMethod::factory()->make();

        $this->brand->makeCurrent();

        $response = $this->patch(route('admin.transaction-methods.update', ['transaction_method' => $transactionsMethodId]),
            $data->toArray());

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function unauthorized(): void
    {
        $this->brand->makeCurrent();

        $transactionsMethod = TransactionsMethod::factory()->create();

        $response = $this->patch(route('admin.transaction-methods.update', ['transaction_method' => $transactionsMethod]));

        $response->assertUnauthorized();
    }
}
