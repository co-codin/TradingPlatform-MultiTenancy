<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Department\Admin;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Modules\Department\Enums\DepartmentPermission;
use Modules\Department\Models\Department;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class UpdateTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;
    use WithFaker;

    /**
     * Test authorized user can update department.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_update_department(): void
    {
        $this->authenticateWithPermission(DepartmentPermission::fromValue(DepartmentPermission::EDIT_DEPARTMENTS));

        $this->brand->makeCurrent();

        $department = Department::factory()->create(static::demoData());
        $data = Department::factory()->make(static::demoData())->toArray();

        $response = $this->patchJson(route('admin.departments.update', ['department' => $department->id]), $data);

        $response->assertOk();

        $response->assertJson([
            'data' => $data,
        ]);
    }

    /**
     * Test unauthorized user can`t update department.
     *
     * @return void
     *
     * @test
     */
    public function unauthorized_user_cant_update_department(): void
    {
        $this->brand->makeCurrent();

        $department = Department::factory()->create();
        $data = Department::factory()->make();

        $response = $this->patchJson(route('admin.departments.update', ['department' => $department->id]), $data->toArray());

        $response->assertUnauthorized();
    }

    /**
     * Test department name exist.
     *
     * @return void
     *
     * @test
     */
    public function department_name_exist(): void
    {
        $this->authenticateWithPermission(DepartmentPermission::fromValue(DepartmentPermission::EDIT_DEPARTMENTS));

        $this->brand->makeCurrent();

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
     *
     * @test
     */
    public function department_title_exist(): void
    {
        $this->authenticateWithPermission(DepartmentPermission::fromValue(DepartmentPermission::EDIT_DEPARTMENTS));

        $this->brand->makeCurrent();

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
     *
     * @test
     */
    public function department_name_is_filled(): void
    {
        $this->authenticateWithPermission(DepartmentPermission::fromValue(DepartmentPermission::EDIT_DEPARTMENTS));

        $this->brand->makeCurrent();

        $department = Department::factory()->create();
        $data = Department::factory()->make(['name' => null])->toArray();

        $response = $this->patchJson(route('admin.departments.update', ['department' => $department->id]), $data);

        $response->assertUnprocessable();
    }

    /**
     * Test department title filled.
     *
     * @return void
     *
     * @test
     */
    public function department_title_is_filled(): void
    {
        $this->authenticateWithPermission(DepartmentPermission::fromValue(DepartmentPermission::EDIT_DEPARTMENTS));

        $this->brand->makeCurrent();

        $department = Department::factory()->create();
        $data = Department::factory()->make(['title' => null])->toArray();

        $response = $this->patchJson(route('admin.departments.update', ['department' => $department->id]), $data);

        $response->assertUnprocessable();
    }

    /**
     * Test department name is string.
     *
     * @return void
     *
     * @test
     */
    public function department_name_is_string(): void
    {
        $this->authenticateWithPermission(DepartmentPermission::fromValue(DepartmentPermission::EDIT_DEPARTMENTS));

        $this->brand->makeCurrent();

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
     *
     * @test
     */
    public function department_title_is_string(): void
    {
        $this->authenticateWithPermission(DepartmentPermission::fromValue(DepartmentPermission::EDIT_DEPARTMENTS));

        $this->brand->makeCurrent();

        $department = Department::factory()->create();
        $data = Department::factory()->make();
        $data->title = 1;

        $response = $this->patchJson(route('admin.departments.update', ['department' => $department->id]), $data->toArray());

        $response->assertUnprocessable();
    }

    /**
     * Demo Data
     *
     * @return array
     */
    private function demoData(): array
    {
        return [
            'name' => $this->faker->name,
            'title' => $this->faker->title . Str::random(5),
            'is_active' => $this->faker->boolean(),
            'is_default' => $this->faker->boolean(),
        ];
    }
}
