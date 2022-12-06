<?php

namespace Tests\Feature\Modules\Department\Admin;

use Modules\Brand\Services\BrandDBService;
use Modules\Department\Enums\DepartmentPermission;
use Modules\Department\Models\Department;
use Modules\Role\Models\Permission;
use Modules\User\Models\User;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\BrandTestCase;
use Tests\TestCase;
use Tests\Traits\HasAuth;

class CreateTest extends BrandTestCase
{
    use HasAuth;

    /**
     * Test authorized user can create department.
     *
     * @return void
     */
    public function test_authorized_user_can_create_department(): void
    {
        $this->authenticateWithPermission(DepartmentPermission::fromValue(DepartmentPermission::CREATE_DEPARTMENTS));

        $data = Department::factory()->make();

        $this->expectsJobs(CreateTenantDatabase::class);

        $this->migrateModules([BrandDBService::ALLOWED_MODULES['Department']]);

//            $response->assertStatus(ResponseAlias::HTTP_ACCEPTED);


        $response = $this->post(route('admin.departments.store'), $data->toArray());
        $response->assertCreated();

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
     * Test unauthorized user can`t create department.
     *
     * @return void
     */
    public function test_unauthorized_user_cant_create_department(): void
    {
        $data = Department::factory()->make();

        $response = $this->postJson(route('admin.departments.store'), $data->toArray());

        $response->assertUnauthorized();
    }

    /**
     * Test department name exist.
     *
     * @return void
     */
    public function test_department_name_exist(): void
    {
        $this->authenticateWithPermission(DepartmentPermission::fromValue(DepartmentPermission::CREATE_DEPARTMENTS));

        $department = Department::factory()->create();

        $data = Department::factory()->make(['name' => $department->name]);

        $response = $this->postJson(route('admin.departments.store'), $data->toArray());

        $response->assertUnprocessable();
    }

    /**
     * Test department title exist.
     *
     * @return void
     */
    public function test_department_title_exist(): void
    {
        $this->authenticateWithPermission(DepartmentPermission::fromValue(DepartmentPermission::CREATE_DEPARTMENTS));

        $department = Department::factory()->create();

        $data = Department::factory()->make(['title' => $department->title]);

        $response = $this->postJson(route('admin.departments.store'), $data->toArray());

        $response->assertUnprocessable();
    }

    /**
     * Test department name required.
     *
     * @return void
     */
    public function test_department_name_is_required(): void
    {
        $this->authenticateWithPermission(DepartmentPermission::fromValue(DepartmentPermission::CREATE_DEPARTMENTS));

        $data = Department::factory()->make()->toArray();
        unset($data['name']);

        $response = $this->postJson(route('admin.departments.store'), $data);

        $response->assertUnprocessable();
    }

    /**
     * Test department title required.
     *
     * @return void
     */
    public function test_department_title_is_required(): void
    {
        $this->authenticateWithPermission(DepartmentPermission::fromValue(DepartmentPermission::CREATE_DEPARTMENTS));

        $data = Department::factory()->make()->toArray();
        unset($data['title']);

        $response = $this->postJson(route('admin.departments.store'), $data);

        $response->assertUnprocessable();
    }

    /**
     * Test department name is string.
     *
     * @return void
     */
    public function test_department_name_is_string(): void
    {
        $this->authenticateWithPermission(DepartmentPermission::fromValue(DepartmentPermission::CREATE_DEPARTMENTS));

        $data = Department::factory()->make();
        $data->name = 1;

        $response = $this->postJson(route('admin.departments.store'), $data->toArray());

        $response->assertUnprocessable();
    }

    /**
     * Test department title is string.
     *
     * @return void
     */
    public function test_department_title_is_string(): void
    {
        $this->authenticateWithPermission(DepartmentPermission::fromValue(DepartmentPermission::CREATE_DEPARTMENTS));

        $data = Department::factory()->make();
        $data->title = 1;

        $response = $this->postJson(route('admin.departments.store'), $data->toArray());

        $response->assertUnprocessable();
    }
}
