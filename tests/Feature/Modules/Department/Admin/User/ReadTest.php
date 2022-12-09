<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Department\User\Admin;

use Modules\Department\Models\Department;
use Modules\User\Enums\UserPermission;
use Modules\User\Models\User;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class ReadTest extends BrandTestCase
{
    use HasAuth;

    /**
     * @test
     */
    public function test_get_departments_users_list(): void
    {
        $this->authenticateWithPermission(UserPermission::fromValue(UserPermission::VIEW_DEPARTMENT_USERS));

        $departments = Department::factory(2)->create();
        $this->user->departments()->syncWithoutDetaching($departments);

        for ($i = 1; $i <= 2; $i++) {
            $department = $departments->skip($i - 1)->first();

            $department->users()->syncWithoutDetaching(User::factory($i)->create());
        }

        $response = $this->get(route('admin.departments.users.allByDepartments'));

        $response->assertSuccessful();
    }

    /**
     * @test
     */
    public function test_user_cant_get_department_users_from_another_department(): void
    {
        $this->authenticateWithPermission(UserPermission::fromValue(UserPermission::VIEW_DEPARTMENT_USERS));

        $departments = Department::factory(2)->create();
        $this->user->departments()->syncWithoutDetaching($departments);

        for ($i = 1; $i <= 2; $i++) {
            $department = $departments->skip($i - 1)->first();

            $department->users()->syncWithoutDetaching(User::factory($i)->create());
        }

        $anotherUser = User::factory()->create();
        $anotherUser->departments()->syncWithoutDetaching(Department::factory()->create());

        $response = $this->get(route('admin.departments.users.allByDepartments'));

        $response->assertJsonMissing([
            'data' => [
                [
                    'id' => $anotherUser->id,
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function test_unauthorized_user_cant_get_departments_users_list(): void
    {
        $departments = Department::factory(2)->create();
        $this->user?->departments()->syncWithoutDetaching($departments);

        for ($i = 1; $i <= 2; $i++) {
            $department = $departments->skip($i - 1)->first();

            $department->users()->syncWithoutDetaching(User::factory($i)->create());
        }

        $response = $this->get(route('admin.departments.users.allByDepartments'));

        $response->assertUnauthorized();
    }
}
