<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Role\Permission\Associations;

use Modules\Role\Database\Seeders\ColumnsTableSeeder;
use Modules\Role\Enums\PermissionPermission;
use Modules\Role\Models\Column;
use Modules\Role\Models\Permission;
use Tests\TestCase;

final class AssociateColumnTest extends TestCase
{
    /**
     * @test
     */
    public function user_can_update(): void
    {
        $this->authenticateWithPermission(PermissionPermission::fromValue(PermissionPermission::EDIT_PERMISSIONS));

        $permission = Permission::factory()->create();
        $this->seed(ColumnsTableSeeder::class);
        $response = $this->put(route('admin.permissions.columns.update', ['id' => $permission->id]), [
            'columns' => [
                Column::all()->random(),
                Column::all()->random(),
            ],
        ]);

        $response->assertOk();
    }

    /**
     * @test
     */
    public function user_can_update_not_found(): void
    {
        $this->authenticateWithPermission(PermissionPermission::fromValue(PermissionPermission::EDIT_PERMISSIONS));

        $this->seed(ColumnsTableSeeder::class);
        $response = $this->put(route('admin.permissions.columns.update', ['id' => 10]), [
            'columns' => [
                Column::all()->random(),
            ],
        ]);

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function can_not_update(): void
    {
        $this->authenticateUser();

        $permission = Permission::factory()->create();
        $this->seed(ColumnsTableSeeder::class);
        $response = $this->put(route('admin.permissions.columns.update', ['id' => $permission->id]), [
            'columns' => [
                Column::all()->random(),
            ],
        ]);

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_unauthorized(): void
    {
        $response = $this->put(route('admin.permissions.columns.update', ['id' => 1]));

        $response->assertUnauthorized();
    }
}
