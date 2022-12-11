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

final class CreateTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;
    use WithFaker;

    /**
     * Test authorized user can create department.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_create_department(): void
    {
        $this->authenticateWithPermission(DepartmentPermission::fromValue(DepartmentPermission::CREATE_DEPARTMENTS));

        $this->brand->makeCurrent();

        $data = Department::factory()->make(static::demoData())->toArray();

        $response = $this->post(route('admin.departments.store'), $data);

        $response->assertCreated();

        $response->assertJson(['data' => $data]);
    }

    /**
     * Test unauthorized user can`t create department.
     *
     * @return void
     *
     * @test
     */
    public function unauthorized_user_cant_create_department(): void
    {
        $this->brand->makeCurrent();

        $data = Department::factory()->make(static::demoData());

        $response = $this->postJson(route('admin.departments.store'), $data->toArray());

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
        $this->authenticateWithPermission(DepartmentPermission::fromValue(DepartmentPermission::CREATE_DEPARTMENTS));

        $this->brand->makeCurrent();

        $department = Department::factory()->create(static::demoData());

        $data = Department::factory()->make(['name' => $department->name]);

        $response = $this->postJson(route('admin.departments.store'), $data->toArray());

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
        $this->authenticateWithPermission(DepartmentPermission::fromValue(DepartmentPermission::CREATE_DEPARTMENTS));

        $this->brand->makeCurrent();

        $department = Department::factory()->create(static::demoData());

        $data = Department::factory()->make(['title' => $department->title]);

        $response = $this->postJson(route('admin.departments.store'), $data->toArray());

        $response->assertUnprocessable();
    }

    /**
     * Test department name required.
     *
     * @return void
     *
     * @test
     */
    public function department_name_is_required(): void
    {
        $this->authenticateWithPermission(DepartmentPermission::fromValue(DepartmentPermission::CREATE_DEPARTMENTS));

        $this->brand->makeCurrent();

        $data = Department::factory()->make(static::demoData())->toArray();
        unset($data['name']);

        $response = $this->postJson(route('admin.departments.store'), $data);

        $response->assertUnprocessable();
    }

    /**
     * Test department title required.
     *
     * @return void
     *
     * @test
     */
    public function department_title_is_required(): void
    {
        $this->authenticateWithPermission(DepartmentPermission::fromValue(DepartmentPermission::CREATE_DEPARTMENTS));

        $this->brand->makeCurrent();

        $data = Department::factory()->make(static::demoData())->toArray();
        unset($data['title']);

        $response = $this->postJson(route('admin.departments.store'), $data);

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
        $this->authenticateWithPermission(DepartmentPermission::fromValue(DepartmentPermission::CREATE_DEPARTMENTS));

        $this->brand->makeCurrent();

        $data = Department::factory()->make(static::demoData());
        $data->name = 1;

        $response = $this->postJson(route('admin.departments.store'), $data->toArray());

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
        $this->authenticateWithPermission(DepartmentPermission::fromValue(DepartmentPermission::CREATE_DEPARTMENTS));

        $this->brand->makeCurrent();

        $data = Department::factory()->make(static::demoData());
        $data->title = 1;

        $response = $this->postJson(route('admin.departments.store'), $data->toArray());

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
            'name' => $this->faker->name . Str::random(5),
            'title' => $this->faker->title . Str::random(5),
            'is_active' => $this->faker->boolean(),
            'is_default' => $this->faker->boolean(),
        ];
    }
}
