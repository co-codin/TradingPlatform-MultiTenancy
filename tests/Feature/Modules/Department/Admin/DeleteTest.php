<?php

namespace Tests\Feature\Modules\Department\Admin;

use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Department\Enums\DepartmentPermission;
use Modules\Department\Models\Department;
use Modules\Role\Models\Permission;
use Modules\User\Models\User;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => 'admin@admin.com'
        ])
            ->givePermissionTo(Permission::factory()->create([
                'name' => DepartmentPermission::DELETE_DEPARTMENTS,
            ])?->name);
    }

    /**
     * @inheritDoc
     */
    public function actingAs(UserContract $user, $guard = null): TestCase
    {
        return parent::actingAs($user, $guard ?: User::DEFAULT_AUTH_GUARD);
    }

    /**
     * Test authorized user can delete department.
     *
     * @return void
     */
    public function test_authorized_user_can_delete_department(): void
    {
        $department = Department::factory()->create();

        $response = $this->actingAs($this->user)->deleteJson(route('admin.departments.destroy', ['department' => $department->id]));

        $response->assertNoContent();
    }

    /**
     * Test unauthorized user can`t delete department.
     *
     * @return void
     */
    public function test_unauthorized_user_cant_delete_department(): void
    {
        $department = Department::factory()->create();

        $response = $this->patchJson(route('admin.departments.destroy', ['department' => $department->id]));

        $response->assertUnauthorized();
    }
}
