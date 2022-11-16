<?php

namespace Tests\Feature\Modules\Department\Admin;

use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Department\Enums\DepartmentPermission;
use Modules\Department\Models\Department;
use Modules\Role\Models\Permission;
use Modules\User\Models\User;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test authorized user can update department.
     *
     * @return void
     */
    public function test_authorized_user_can_update_department(): void
    {
        $this->authenticateWithPermission(DepartmentPermission::fromValue(DepartmentPermission::EDIT_DEPARTMENTS));

        $department = Department::factory()->create();
        $data = Department::factory()->make();

        $response = $this->patchJson(route('admin.departments.update', ['department' => $department->id]), $data->toArray());

        $response->assertOk();

        $response->assertJson([
            'data' => [
                'name' => $data['name'],
                'title' => $data['title'],
                'is_active' => $data['is_active'],
                'is_default' => $data['is_default'],
            ],
        ]);
    }

    /**
     * Test unauthorized user can`t update department.
     *
     * @return void
     */
    public function test_unauthorized_user_cant_update_department(): void
    {
        $department = Department::factory()->create();
        $data = Department::factory()->make();

        $response = $this->patchJson(route('admin.departments.update', ['department' => $department->id]), $data->toArray());

        $response->assertUnauthorized();
    }

    /**
     * Test department name exist.
     *
     * @return void
     */
    public function test_department_name_exist(): void
    {
        $this->authenticateWithPermission(DepartmentPermission::fromValue(DepartmentPermission::EDIT_DEPARTMENTS));

        $department = Department::factory()->create();
        $targetDepartment = Department::factory()->create();
        $data = Department::factory()->make(['name' => $department->name]);

        $response = $this->patchJson(route('admin.departments.update', ['department' => $targetDepartment->id]), $data->toArray());

        $response->assertUnprocessable();
    }

    /**
     * Test department title exist.
     *
     * @return void
     */
    public function test_department_title_exist(): void
    {
        $this->authenticateWithPermission(DepartmentPermission::fromValue(DepartmentPermission::EDIT_DEPARTMENTS));

        $department = Department::factory()->create();
        $targetDepartment = Department::factory()->create();
        $data = Department::factory()->make(['title' => $department->title]);

        $response = $this->patchJson(route('admin.departments.update', ['department' => $targetDepartment->id]), $data->toArray());

        $response->assertUnprocessable();
    }

    /**
     * Test department name filled.
     *
     * @return void
     */
    public function test_department_name_is_filled(): void
    {
        $this->authenticateWithPermission(DepartmentPermission::fromValue(DepartmentPermission::EDIT_DEPARTMENTS));

        $department = Department::factory()->create();
        $data = Department::factory()->make(['name' => null])->toArray();

        $response = $this->patchJson(route('admin.departments.update', ['department' => $department->id]), $data);

        $response->assertUnprocessable();
    }

    /**
     * Test department title filled.
     *
     * @return void
     */
    public function test_department_title_is_filled(): void
    {
        $this->authenticateWithPermission(DepartmentPermission::fromValue(DepartmentPermission::EDIT_DEPARTMENTS));

        $department = Department::factory()->create();
        $data = Department::factory()->make(['title' => null])->toArray();

        $response = $this->patchJson(route('admin.departments.update', ['department' => $department->id]), $data);

        $response->assertUnprocessable();
    }

    /**
     * Test department name is string.
     *
     * @return void
     */
    public function test_department_name_is_string(): void
    {
        $this->authenticateWithPermission(DepartmentPermission::fromValue(DepartmentPermission::EDIT_DEPARTMENTS));

        $department = Department::factory()->create();
        $data = Department::factory()->make();
        $data->name = 1;

        $response = $this->patchJson(route('admin.departments.update', ['department' => $department->id]), $data->toArray());

        $response->assertUnprocessable();
    }

    /**
     * Test department title is string.
     *
     * @return void
     */
    public function test_department_title_is_string(): void
    {
        $this->authenticateWithPermission(DepartmentPermission::fromValue(DepartmentPermission::EDIT_DEPARTMENTS));

        $department = Department::factory()->create();
        $data = Department::factory()->make();
        $data->title = 1;

        $response = $this->patchJson(route('admin.departments.update', ['department' => $department->id]), $data->toArray());

        $response->assertUnprocessable();
    }
}
