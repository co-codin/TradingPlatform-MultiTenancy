<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Transaction\Admin\Method;

use Modules\Transaction\Enums\TransactionsMethodPermission;
use Modules\Transaction\Models\TransactionsMethod;
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
        $this->authenticateWithPermission(
            TransactionsMethodPermission::fromValue(TransactionsMethodPermission::CREATE_TRANSACTION_METHOD)
        );

        $this->brand->makeCurrent();

        $data = TransactionsMethod::factory()->make()->toArray();

        $response = $this->post(route('admin.transaction-methods.store'), $data);

        $response->assertCreated();
        $response->assertJson(['data' => $data]);
    }

    /**
     * @test
     */
    public function can_not_create(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $data = TransactionsMethod::factory()->make()->toArray();

        $response = $this->post(route('admin.transaction-methods.store'), $data);

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_unauthorized(): void
    {
        $response = $this->post(route('admin.transaction-methods.store'));

        $response->assertUnauthorized();
    }
}
