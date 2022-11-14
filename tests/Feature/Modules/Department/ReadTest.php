<?php

namespace Tests\Feature\Modules\Department;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Department\Enums\DepartmentPermission;
use Modules\Department\Models\Department;
use Modules\Role\Models\Permission;
use Modules\User\Models\User;
use Tests\TestCase;

class ReadTest extends TestCase
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
                'name' => DepartmentPermission::VIEW_DEPARTMENTS,
            ])?->name);
    }

    /**
     * Test authorized user can get departments list.
     *
     * @return void
     */
    public function test_authorized_user_can_get_departments_list(): void
    {
        $department = Department::factory()->create();

        $response = $this->actingAs($this->user)->getJson(route('admin.departments.index'));

        $response->assertOk();

        $response->assertJson([
            'data' => [
                [
                    'id' => $department->id,
                    'name' => $department->name,
                    'title' => $department->title,
                    'is_active' => $department->is_active,
                    'is_default' => $department->is_default,
                ]
            ]
        ]);
    }
    /**
     * Test unauthorized user cant get departments list.
     *
     * @return void
     */
    public function test_unauthorized_user_cant_get_departments_list(): void
    {
        Department::factory()->create();

        $response = $this->getJson(route('admin.departments.index'));

        $response->assertUnauthorized();
    }
    /**
     * Test authorized user can get department.
     *
     * @return void
     */
    public function test_authorized_user_can_get_country(): void
    {
        $department = Department::factory()->create();

        $response = $this->actingAs($this->user)->getJson(route('admin.departments.show', ['department' => $department->id]));

        $response->assertOk();

        $response->assertJson([
            'data' => [
                'id' => $department->id,
                'name' => $department->name,
                'title' => $department->title,
                'is_active' => $department->is_active,
                'is_default' => $department->is_default,
            ]
        ]);
    }
    /**
     * Test unauthorized user cant get department.
     *
     * @return void
     */
    public function test_unauthorized_user_cant_get_country(): void
    {
        $department = Department::factory()->create();

        $response = $this->getJson(route('admin.departments.show', ['department' => $department->id]));

        $response->assertUnauthorized();
    }
}
