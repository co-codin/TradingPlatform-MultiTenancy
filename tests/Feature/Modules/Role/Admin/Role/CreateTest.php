<?php


namespace Tests\Feature\Modules\Role\Admin\Role;


use Modules\Role\Enums\PermissionLevel;
use Modules\Role\Models\Permission;
use Modules\Role\Models\Role;
use Tests\TestCase;

class CreateTest extends TestCase
{
    public function test_authenticated_can_create_role()
    {
        $this->authenticateAdmin();

        $roleData = array_merge(Role::factory()->raw(), [
            'permissions' => [
                [
                    'id' => $firstPermission = Permission::factory()->create()->id,
                    'level' => PermissionLevel::getRandomValue()
                ],
                [
                    'id' => $secondPermission = Permission::factory()->create()->id,
                    'level' => PermissionLevel::getRandomValue()
                ]
            ]
        ]);

        $response = $this->json('POST', route('admin.roles.store'), $roleData);

        $response->assertCreated();
        $response->assertJsonStructure([
            'data' => [
                'name',
                'guard_name',
                'key',
            ]
        ]);

        $this->assertDatabaseHas('roles', [
            'name' => $roleData['name'],
        ]);

        $this->assertDatabaseHas('role_has_permissions', [
            'permission_id' => $firstPermission,
            'role_id' => $response->json()['data']['id'],
        ]);

        $this->assertDatabaseHas('role_has_permissions', [
            'permission_id' => $secondPermission,
            'role_id' => $response->json()['data']['id'],
        ]);
    }
}
