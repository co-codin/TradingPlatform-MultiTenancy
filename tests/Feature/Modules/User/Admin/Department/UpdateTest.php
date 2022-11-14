<?php

namespace Tests\Feature\Modules\User\Admin\Department;

use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Department\Enums\DepartmentPermission;
use Modules\Department\Models\Department;
use Modules\Role\Models\Permission;
use Modules\User\Enums\UserDepartmentPermission;
use Modules\User\Enums\UserPermission;
use Modules\User\Models\User;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @inheritDoc
     */
    public function actingAs(UserContract $user, $guard = null): TestCase
    {
        return parent::actingAs($user, $guard ?: User::DEFAULT_AUTH_GUARD);
    }

    /**
     * Test authorized user can update self department.
     *
     * @return void
     */
    public function test_authorized_user_can_update_self_department(): void
    {
        $user = User::factory()->create();
        $data = Department::factory(5)->create();

        $response = $this->actingAs($user)
            ->putJson(
                route('admin.users.department.update', ['user' => $user->id]),
                ['departments' => $data->pluck('id')->toArray()]
            );

        $response->assertOk();

        $response->assertJson([
            'data' => [
                'id' => $user->id,
                'departments' => $data->toArray(),
            ],
        ]);
    }

    /**
     * Test authorized user cant update another user department.
     *
     * @return void
     */
    public function test_authorized_user_cant_update_another_user_department(): void
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();
        $data = Department::factory(5)->create();

        $response = $this->actingAs($user)
            ->putJson(
                route('admin.users.department.update', ['user' => $anotherUser->id]),
                ['departments' => $data->pluck('id')->toArray()]
            );

        $response->assertForbidden();
    }

    /**
     * Test authorized user with edit permissions can update another user department.
     *
     * @return void
     */
    public function test_authorized_user_with_edit_permissions_can_update_another_user_department(): void
    {
        $user = User::factory()->create()
            ->givePermissionTo(Permission::factory()->create([
                'name' => UserPermission::EDIT_USERS,
            ])?->name);

        $anotherUser = User::factory()->create();
        $data = Department::factory(5)->create();

        $response = $this->actingAs($user)
            ->putJson(
                route('admin.users.department.update', ['user' => $anotherUser->id]),
                ['departments' => $data->pluck('id')->toArray()]
            );

        $response->assertOk();

        $response->assertJson([
            'data' => [
                'id' => $anotherUser->id,
                'departments' => $data->toArray(),
            ],
        ]);
    }

    /**
     * Test authorized user with edit permissions can update another user department.
     *
     * @return void
     */
    public function test_authorized_user_cant_update_non_existent_department(): void
    {
        $user = User::factory()->create();
        $unExistedIds[] = Department::orderByDesc('id')->first()?->id + 1 ?? 1;

        for ($i = 0; $i < 4; $i++) {
            $unExistedIds[] = $unExistedIds[$i] + 1;
        }

        $response = $this->actingAs($user)
            ->putJson(
                route('admin.users.department.update', ['user' => $user->id]),
                ['departments' => $unExistedIds]
            );

        $response->assertUnprocessable();
    }

    /**
     * Test unauthorized user can`t update self department.
     *
     * @return void
     */
    public function test_unauthorized_user_cant_update_self_department(): void
    {
        $user = User::factory()->create();
        $data = Department::factory(5)->create();

        $response = $this->putJson(
            route('admin.users.department.update', ['user' => $user->id]),
            $data->pluck('id')->toArray()
        );

        $response->assertUnauthorized();
    }
}
