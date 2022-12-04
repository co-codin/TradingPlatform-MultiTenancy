<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Cutomer\Admin;

use Maatwebsite\Excel\Facades\Excel;
use Modules\User\Enums\UserPermission;
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

        $this->authenticateWithPermission(UserPermission::fromValue(UserPermission::IMPORT_CUSTOMERS));

        $response = $this->post(route('admin.customers.import.excel'));

        $response->assertSuccessful();
    }

    /**
     * @test
     */
    public function test_import_csv_customers(): void
    {
        Excel::fake();

        $this->authenticateWithPermission(UserPermission::fromValue(UserPermission::IMPORT_CUSTOMERS));

        $response = $this->post(route('admin.customers.import.csv'));

        $response->assertSuccessful();
    }
}
