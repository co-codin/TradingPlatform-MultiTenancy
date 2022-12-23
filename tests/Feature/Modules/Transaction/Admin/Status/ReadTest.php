<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Transaction\Admin\Status;

use Modules\Communication\Enums\CommentPermission;
use Modules\Communication\Models\Comment;
use Modules\Transaction\Enums\TransactionStatusPermission;
use Modules\Transaction\Models\TransactionStatus;
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
            TransactionStatusPermission::fromValue(TransactionStatusPermission::VIEW_TRANSACTION_STATUSES)
        );

        $this->brand->makeCurrent();

        $transactionStatuses = TransactionStatus::factory(10)->create();

        $response = $this->get(route('admin.transaction-statuses.index'));

        $response->assertOk();

        $response->assertJson([
            'data' => $transactionStatuses->only('id')->toArray(),
        ]);
    }

    /**
     * @test
     */
    public function can_not_view_any(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $response = $this->get(route('admin.transaction-statuses.index'));

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function can_view(): void
    {
        $this->authenticateWithPermission(
            TransactionStatusPermission::fromValue(TransactionStatusPermission::VIEW_TRANSACTION_STATUSES)
        );

        $this->brand->makeCurrent();

        $transactionStatus = TransactionStatus::factory()->create();

        $response = $this->get(route('admin.transaction-statuses.show', ['transaction_status' => $transactionStatus]));

        $response->assertOk();
        $response->assertJson(['data' => $transactionStatus->toArray()]);
    }

    /**
     * @test
     */
    public function can_not_view(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();
        $transactionStatus = TransactionStatus::factory()->create();

        $response = $this->get(route('admin.transaction-statuses.show', ['transaction_status' => $transactionStatus]));

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_found(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();
        $transactionStatusId = TransactionStatus::query()->orderByDesc('id')->first()?->id + 1 ?? 1;

        $response = $this->get(route('admin.transaction-statuses.show', ['transaction_status' => $transactionStatusId]));

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function unauthorized_view_any(): void
    {
        $response = $this->get(route('admin.transaction-statuses.index'));

        $response->assertUnauthorized();
    }

    /**
     * @test
     */
    public function not_unauthorized_view(): void
    {
        $this->brand->makeCurrent();
        $transactionStatus = TransactionStatus::factory()->create();

        $response = $this->get(route('admin.transaction-statuses.show', ['transaction_status' => $transactionStatus]));

        $response->assertUnauthorized();
    }
}
