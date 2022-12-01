<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Department\User;

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
        $this->user->departments()->sync($departments);

        for ($i = 1; $i <= 2; $i++) {
            $department = $departments->skip($i - 1)->first();

            $department->users()->sync(User::factory($i)->create());
        }

        $response = $this->get(route('admin.departments.users.allByDepartments'));

        $response->assertSuccessful();
    }

    /**
     * @test
     */
    public function test_unauthorized_user_cant_get_departments_users_list(): void
    {
        $departments = Department::factory(2)->create();
        $this->user?->departments()->sync($departments);

        for ($i = 1; $i <= 2; $i++) {
            $department = $departments->skip($i - 1)->first();

            $department->users()->sync(User::factory($i)->create());
        }

        $response = $this->get(route('admin.departments.users.allByDepartments'));

        $response->assertUnauthorized();
    }
}
