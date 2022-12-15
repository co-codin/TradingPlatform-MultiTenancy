<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Customer\Admin;

use Modules\Customer\Enums\CustomerPermission;
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
    public function test_export_excel_customers(): void
    {
        $this->authenticateWithPermission(CustomerPermission::fromValue(CustomerPermission::EXPORT_CUSTOMERS));

        $this->brand->makeCurrent();

        $response = $this->post(route('admin.customers.export.excel'));

        $response->assertSuccessful();
    }

    /**
     * @test
     */
    public function test_export_csv_customers(): void
    {
        $this->authenticateWithPermission(CustomerPermission::fromValue(CustomerPermission::EXPORT_CUSTOMERS));

        $this->brand->makeCurrent();

        $response = $this->post(route('admin.customers.export.csv'));

        $response->assertSuccessful();
    }
}
