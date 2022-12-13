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

final class DeleteTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;
    use WithFaker;

    /**
     * Test authorized user can delete department.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_delete_department(): void
    {
        $this->authenticateWithPermission(DepartmentPermission::fromValue(DepartmentPermission::DELETE_DEPARTMENTS));

        $this->brand->makeCurrent();

        $department = Department::factory()->create(static::demoData());

        $response = $this->deleteJson(route('admin.departments.destroy', ['department' => $department->id]));

        $response->assertNoContent();
    }

    /**
     * Test unauthorized user can`t delete department.
     *
     * @return void
     *
     * @test
     */
    public function unauthorized_user_cant_delete_department(): void
    {
        $this->brand->makeCurrent();

        $department = Department::factory()->create(static::demoData());

        $response = $this->patchJson(route('admin.departments.destroy', ['department' => $department->id]));

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
