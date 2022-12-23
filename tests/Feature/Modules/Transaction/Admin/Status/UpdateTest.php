<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Transaction\Admin\Status;

use Illuminate\Http\UploadedFile;
use Modules\Communication\Enums\CommentPermission;
use Modules\Communication\Models\Comment;
use Modules\Transaction\Enums\TransactionStatusPermission;
use Modules\Transaction\Models\TransactionStatus;
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
            TransactionStatusPermission::fromValue(TransactionStatusPermission::EDIT_TRANSACTION_STATUSES)
        );

        $this->brand->makeCurrent();

        $transactionStatus = TransactionStatus::factory()->create();
        $transactionStatusData = TransactionStatus::factory()->make()->toArray();

        $this->brand->makeCurrent();

        $response = $this->patch(route('admin.transaction-statuses.update', ['transaction_status' => $transactionStatus]), $transactionStatusData);

        $response->assertOk();
        $response->assertJson(['data' => $transactionStatusData]);
    }

    /**
     * @test
     */
    public function can_not_update(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $transactionStatus = TransactionStatus::factory()->create();
        $data = TransactionStatus::factory()->make();

        $this->brand->makeCurrent();

        $response = $this->patch(
            route('admin.transaction-statuses.update', ['transaction_status' => $transactionStatus]), $data->toArray()
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

        $transactionStatusId = TransactionStatus::orderByDesc('id')->first()?->id + 1 ?? 1;
        $data = TransactionStatus::factory()->make();

        $this->brand->makeCurrent();

        $response = $this->patch(route('admin.transaction-statuses.update', ['transaction_status' => $transactionStatusId]),
            $data->toArray());

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function unauthorized(): void
    {
        $this->brand->makeCurrent();
        $transactionStatus = TransactionStatus::factory()->create();

        $response = $this->patch(route('admin.transaction-statuses.update', ['transaction_status' => $transactionStatus]));

        $response->assertUnauthorized();
    }
}
