<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Customer;

use Illuminate\Http\UploadedFile;
use Modules\Customer\Enums\CustomerPermission;
use Modules\Desk\Models\Desk;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class ImportTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    /**
     * @test
     */
    public function test_import_excel_customers(): void
    {
        $this->authenticateWithPermission(CustomerPermission::fromValue(CustomerPermission::IMPORT_CUSTOMERS));

        $this->brand->makeCurrent();

        Desk::factory()->create();

        $file = new UploadedFile(
            storage_path('customer-import/laravel-excel.xlsx'),
            'laravel-excel.xlsx',
            null,
            null,
            true
        );

        $response = $this->post(route('admin.customers.import.excel'), ['file' => $file]);

        $response->assertSuccessful();
    }

    /**
     * @test
     */
    public function test_import_csv_customers(): void
    {
        $this->authenticateWithPermission(CustomerPermission::fromValue(CustomerPermission::IMPORT_CUSTOMERS));

        $this->brand->makeCurrent();

        Desk::factory()->create();

        $file = new UploadedFile(
            storage_path('customer-import/laravel-excel.csv'),
            'laravel-excel.csv',
            null,
            null,
            true
        );

        $response = $this->post(route('admin.customers.import.csv'), ['file' => $file]);

        $response->assertSuccessful();
    }
}
