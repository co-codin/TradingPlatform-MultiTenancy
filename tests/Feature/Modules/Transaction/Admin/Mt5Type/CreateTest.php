<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Transaction\Admin\Mt5Type;

use Modules\Transaction\Enums\TransactionsMt5TypePermission;
use Modules\Transaction\Models\TransactionsMt5Type;
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
            TransactionsMt5TypePermission::fromValue(TransactionsMt5TypePermission::CREATE_TRANSACTION_MT5_TYPE)
        );

        $this->brand->makeCurrent();

        $data = TransactionsMt5Type::factory()->make()->toArray();

        $response = $this->post(route('admin.transaction-mt5-types.store'), $data);

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

        $data = TransactionsMt5Type::factory()->make()->toArray();

        $response = $this->post(route('admin.transaction-mt5-types.store'), $data);

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_unauthorized(): void
    {
        $response = $this->post(route('admin.transaction-mt5-types.store'));

        $response->assertUnauthorized();
    }
}
