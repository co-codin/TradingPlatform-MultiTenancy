<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Transaction\Admin\Status;

use Modules\Transaction\Enums\TransactionStatusPermission;
use Modules\Transaction\Models\TransactionStatus;
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
            TransactionStatusPermission::fromValue(TransactionStatusPermission::CREATE_TRANSACTION_STATUSES)
        );

        $this->brand->makeCurrent();

        $data = TransactionStatus::factory()->make()->toArray();

        $response = $this->post(route('admin.transaction-statuses.store'), $data);

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

        $data = TransactionStatus::factory()->make()->toArray();

        $response = $this->post(route('admin.transaction-statuses.store'), $data);

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_unauthorized(): void
    {
        $response = $this->post(route('admin.transaction-statuses.store'));

        $response->assertUnauthorized();
    }
}
