<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Cutomer\Admin;

use Maatwebsite\Excel\Facades\Excel;
use Modules\Customer\Enums\CustomerPermission;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class ImportTest extends BrandTestCase
{
    use HasAuth;

    /**
     * @test
     */
    public function test_import_excel_customers(): void
    {
        Excel::fake();

        $this->authenticateWithPermission(CustomerPermission::fromValue(CustomerPermission::IMPORT_CUSTOMERS));

        $response = $this->post(route('admin.customers.import.excel'));

        $response->assertSuccessful();
    }

    /**
     * @test
     */
    public function test_import_csv_customers(): void
    {
        Excel::fake();

        $this->authenticateWithPermission(CustomerPermission::fromValue(CustomerPermission::IMPORT_CUSTOMERS));

        $response = $this->post(route('admin.customers.import.csv'));

        $response->assertSuccessful();
    }
}
