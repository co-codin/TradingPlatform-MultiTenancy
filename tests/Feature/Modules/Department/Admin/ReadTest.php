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

final class ReadTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;
    use WithFaker;

    /**
     * Test authorized user can get departments list.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_get_departments_list(): void
    {
        $this->authenticateWithPermission(DepartmentPermission::fromValue(DepartmentPermission::VIEW_DEPARTMENTS));

        $this->brand->makeCurrent();

        $data = Department::factory()->create(static::demoData())->toArray();

        $response = $this->getJson(route('admin.departments.index'));

        $response->assertOk();

        $response->assertJson([
            'data' => [$data],
        ]);
    }

    /**
     * Test unauthorized user cant get departments list.
     *
     * @return void
     *
     * @test
     */
    public function unauthorized_user_cant_get_departments_list(): void
    {
        $this->brand->makeCurrent();

        Department::factory()->create(static::demoData());

        $response = $this->getJson(route('admin.departments.index'));

        $response->assertUnauthorized();
    }

    /**
     * Test authorized user can get department.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_get_country(): void
    {
        $this->authenticateWithPermission(DepartmentPermission::fromValue(DepartmentPermission::VIEW_DEPARTMENTS));

        $this->brand->makeCurrent();

        $data = Department::factory()->create(static::demoData());

        $response = $this->getJson(route('admin.departments.show', ['department' => $data->id]));

        $response->assertOk();

        $response->assertJson([
            'data' => $data->toArray(),
        ]);
    }

    /**
     * Test unauthorized user cant get department.
     *
     * @return void
     *
     * @test
     */
    public function unauthorized_user_cant_get_country(): void
    {
        $this->brand->makeCurrent();

        $department = Department::factory()->create(static::demoData());

        $response = $this->getJson(route('admin.departments.show', ['department' => $department->id]));

        $response->assertUnauthorized();
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
