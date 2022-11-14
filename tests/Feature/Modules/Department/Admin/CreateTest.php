<?php

namespace Tests\Feature\Modules\Department\Admin;

use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Department\Enums\DepartmentPermission;
use Modules\Department\Models\Department;
use Modules\Role\Models\Permission;
use Modules\User\Models\User;
use Tests\TestCase;

class CreateTest extends TestCase
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
                'name' => DepartmentPermission::CREATE_DEPARTMENTS,
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
     * Test authorized user can create department.
     *
     * @return void
     */
    public function test_authorized_user_can_create_department(): void
    {
        $data = Department::factory()->make();

        $response = $this->actingAs($this->user)->postJson(route('admin.departments.store'), $data->toArray());

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
        $department = Department::factory()->create();

        $data = Department::factory()->make(['name' => $department->name]);

        $response = $this->actingAs($this->user)->postJson(route('admin.departments.store'), $data->toArray());

        $response->assertUnprocessable();
    }

    /**
     * Test department title exist.
     *
     * @return void
     */
    public function test_department_title_exist(): void
    {
        $department = Department::factory()->create();

        $data = Department::factory()->make(['title' => $department->title]);

        $response = $this->actingAs($this->user)->postJson(route('admin.departments.store'), $data->toArray());

        $response->assertUnprocessable();
    }

    /**
     * Test department name required.
     *
     * @return void
     */
    public function test_department_name_is_required(): void
    {
        $data = Department::factory()->make()->toArray();
        unset($data['name']);

        $response = $this->actingAs($this->user)->postJson(route('admin.departments.store'), $data);

        $response->assertUnprocessable();
    }

    /**
     * Test department title required.
     *
     * @return void
     */
    public function test_department_title_is_required(): void
    {
        $data = Department::factory()->make()->toArray();
        unset($data['title']);

        $response = $this->actingAs($this->user)->postJson(route('admin.departments.store'), $data);

        $response->assertUnprocessable();
    }

    /**
     * Test department name is string.
     *
     * @return void
     */
    public function test_department_name_is_string(): void
    {
        $data = Department::factory()->make();
        $data->name = 1;

        $response = $this->actingAs($this->user)->postJson(route('admin.departments.store'), $data->toArray());

        $response->assertUnprocessable();
    }

    /**
     * Test department title is string.
     *
     * @return void
     */
    public function test_department_title_is_string(): void
    {
        $data = Department::factory()->make();
        $data->title = 1;

        $response = $this->actingAs($this->user)->postJson(route('admin.departments.store'), $data->toArray());

        $response->assertUnprocessable();
    }
}
