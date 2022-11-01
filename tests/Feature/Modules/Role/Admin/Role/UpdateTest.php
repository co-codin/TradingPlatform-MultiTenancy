<?php


namespace Tests\Feature\Modules\Role\Admin\Role;


use Modules\Role\Models\Role;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    public function test_authenticated_can_update_role()
    {
        $this->authenticateAdmin();

        $role = Role::factory()->create();

        $response = $this->json('PATCH', route('admin.roles.update', $role), [
            'name' => $newName = 'new name',
            'key' => 'new-key'
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('roles', [
            'name' => $newName,
        ]);
    }
}
