<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\User\Admin;

use Modules\User\Enums\UserPermission;
use Modules\User\Models\User;
use Tests\TestCase;
use Tests\Traits\HasAuth;

final class ExportTest extends TestCase
{
    /**
     * @test
     */
    public function test_export_excel_customers(): void
    {
        $this->authenticateWithPermission(UserPermission::fromValue(UserPermission::EXPORT_USERS));

        User::factory(10)->create();

        $response = $this->get(route('admin.users.export.excel'));

        $response->assertSuccessful();
    }

    /**
     * @test
     */
    public function test_export_csv_customers(): void
    {
        $this->authenticateWithPermission(UserPermission::fromValue(UserPermission::EXPORT_USERS));

        User::factory(10)->create();

        $response = $this->get(route('admin.users.export.csv'));

        $response->assertSuccessful();
    }
}
