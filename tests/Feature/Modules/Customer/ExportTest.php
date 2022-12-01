<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Cutomer\Admin;

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
        $this->authenticateAdmin();

        $response = $this->post(route('admin.customers.export.excel'));

        $response->assertSuccessful();
    }

    /**
     * @test
     */
    public function test_export_csv_customers(): void
    {
        $this->authenticateAdmin();

        $response = $this->post(route('admin.customers.export.csv'));

        $response->assertSuccessful();
    }
}
