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
            TransactionStatusPermission::fromValue(TransactionStatusPermission::DELETE_TRANSACTION_STATUSES)
        );

        $this->brand->makeCurrent();

        $transactionStatus = TransactionStatus::factory()->create();

        $response = $this->delete(route('admin.transaction-statuses.destroy', ['transaction_status' => $transactionStatus]));

        $response->assertNoContent();
    }

    /**
     * @test
     */
    public function cant_delete(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $transactionStatus = TransactionStatus::factory()->create();

        $response = $this->delete(route('admin.transaction-statuses.destroy', ['transaction_status' => $transactionStatus]));

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_found(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $transactionStatusId = TransactionStatus::orderByDesc('id')->first()?->id + 1 ?? 1;

        $response = $this->delete(route('admin.transaction-statuses.destroy', ['transaction_status' => $transactionStatusId]));

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function unauthorized(): void
    {
        $this->brand->makeCurrent();

        $transactionStatus = TransactionStatus::factory()->create();

        $response = $this->delete(route('admin.transaction-statuses.destroy', ['transaction_status' => $transactionStatus]));

        $response->assertUnauthorized();
    }
}
