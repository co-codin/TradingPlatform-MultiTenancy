<?php


namespace Tests\Feature\Modules\Role\Admin\Role;

use Modules\Role\Models\Role;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    public function test_authenticated_can_delete_role()
    {
        $this->authenticateAdmin();

        $role = Role::factory()->create();

        $response = $this->json('DELETE', route('admin.roles.destroy', $role));

        $response->assertNoContent();

        $this->assertDatabaseMissing('roles', [
            'id' => $role->id
        ]);
    }
}
