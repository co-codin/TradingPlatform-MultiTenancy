<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Transaction\Admin;

use Modules\Transaction\Enums\TransactionPermission;
use Modules\Transaction\Models\Transaction;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class ExportTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    /**
     * @test
     */
    public function test_export_excel_transactions(): void
    {
        $this->authenticateWithPermission(TransactionPermission::fromValue(TransactionPermission::EXPORT_TRANSACTIONS));

        $this->brand->makeCurrent();

        $customer = $this->brand->execute(function () {
            return Transaction::factory()->make();
        });

        $customer->save();

        $this->brand->makeCurrent();

        $response = $this->get(route('admin.transactions.export.excel'));

        $response->assertSuccessful();
    }

    /**
     * @test
     */
    public function test_export_csv_transactions(): void
    {
        $this->authenticateWithPermission(TransactionPermission::fromValue(TransactionPermission::EXPORT_TRANSACTIONS));

        $this->brand->makeCurrent();

        $customer = $this->brand->execute(function () {
            return Transaction::factory()->make();
        });

        $customer->save();

        $this->brand->makeCurrent();

        $response = $this->get(route('admin.transactions.export.csv'));

        $response->assertSuccessful();
    }
}
