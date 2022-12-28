<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Transaction\Admin\Method;

use Modules\Transaction\Enums\TransactionsMethodPermission;
use Modules\Transaction\Models\TransactionsMethod;
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
            TransactionsMethodPermission::fromValue(TransactionsMethodPermission::DELETE_TRANSACTION_METHOD)
        );

        $this->brand->makeCurrent();

        $transactionsMethod = TransactionsMethod::factory()->create();

        $response = $this->delete(route('admin.transaction-methods.destroy', ['transaction_method' => $transactionsMethod]));

        $response->assertNoContent();
    }

    /**
     * @test
     */
    public function cant_delete(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $transactionsMethod = TransactionsMethod::factory()->create();

        $response = $this->delete(route('admin.transaction-methods.destroy', ['transaction_method' => $transactionsMethod]));

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

        $response = $this->delete(route('admin.transaction-methods.destroy', ['transaction_method' => $transactionsMethodId]));

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function unauthorized(): void
    {
        $this->brand->makeCurrent();

        $transactionsMethod = TransactionsMethod::factory()->create();

        $response = $this->delete(route('admin.transaction-methods.destroy', ['transaction_method' => $transactionsMethod]));

        $response->assertUnauthorized();
    }
}
