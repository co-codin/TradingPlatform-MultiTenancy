<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Cutomer\Admin;

use Modules\User\Enums\UserPermission;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class ExportTest extends BrandTestCase
{
    use HasAuth;

    /**
     * @test
     */
    public function test_export_excel_customers(): void
    {
        $this->authenticateWithPermission(UserPermission::fromValue(UserPermission::EXPORT_CUSTOMERS));

        $response = $this->post(route('admin.customers.export.excel'));

        $response->assertSuccessful();
    }

    /**
     * @test
     */
    public function test_export_csv_customers(): void
    {
        $this->authenticateWithPermission(UserPermission::fromValue(UserPermission::EXPORT_CUSTOMERS));

        $response = $this->post(route('admin.customers.export.csv'));

        $response->assertSuccessful();
    }
}
