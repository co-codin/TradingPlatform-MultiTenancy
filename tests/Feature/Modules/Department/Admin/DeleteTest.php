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
     * Test authorized user can delete department.
     *
     * @return void
     */
    public function test_authorized_user_can_delete_department(): void
    {
        $this->authenticateWithPermission(DepartmentPermission::fromValue(DepartmentPermission::DELETE_DEPARTMENTS));

        $department = Department::factory()->create();

        $response = $this->deleteJson(route('admin.departments.destroy', ['department' => $department->id]));

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
