<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Transaction\Admin\Method;

use Modules\Transaction\Enums\TransactionsMethodPermission;
use Modules\Transaction\Models\TransactionsMethod;
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
            TransactionsMethodPermission::fromValue(TransactionsMethodPermission::VIEW_TRANSACTION_METHOD)
        );

        $this->brand->makeCurrent();

        $transactionsMethod = TransactionsMethod::factory(10)->create();

        $response = $this->get(route('admin.transaction-methods.index'));

        $response->assertOk();

        $response->assertJson([
            'data' => $transactionsMethod->only('id')->toArray(),
        ]);
    }

    /**
     * @test
     */
    public function can_not_view_any(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $response = $this->get(route('admin.transaction-methods.index'));

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function can_view(): void
    {
        $this->authenticateWithPermission(
            TransactionsMethodPermission::fromValue(TransactionsMethodPermission::VIEW_TRANSACTION_METHOD)
        );

        $this->brand->makeCurrent();

        $transactionsMethod = TransactionsMethod::factory()->create();

        $response = $this->get(route('admin.transaction-methods.show', ['transaction_method' => $transactionsMethod]));

        $response->assertOk();
        $response->assertJson(['data' => $transactionsMethod->toArray()]);
    }

    /**
     * @test
     */
    public function can_not_view(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();
        $transactionsMethod = TransactionsMethod::factory()->create();

        $response = $this->get(route('admin.transaction-methods.show', ['transaction_method' => $transactionsMethod]));

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_found(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();
        $transactionsMethodId = TransactionsMethod::query()->orderByDesc('id')->first()?->id + 1 ?? 1;

        $response = $this->get(route('admin.transaction-methods.show', ['transaction_method' => $transactionsMethodId]));

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function unauthorized_view_any(): void
    {
        $response = $this->get(route('admin.transaction-methods.index'));

        $response->assertUnauthorized();
    }

    /**
     * @test
     */
    public function not_unauthorized_view(): void
    {
        $this->brand->makeCurrent();
        $transactionsMethod = TransactionsMethod::factory()->create();

        $response = $this->get(route('admin.transaction-methods.show', ['transaction_method' => $transactionsMethod]));

        $response->assertUnauthorized();
    }
}
