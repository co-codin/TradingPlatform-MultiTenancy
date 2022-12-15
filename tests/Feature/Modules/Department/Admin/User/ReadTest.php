<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Department\User\Admin;

use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Department\Models\Department;
use Modules\User\Enums\UserPermission;
use Modules\User\Models\User;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class ReadTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;
    use WithFaker;

    /**
     * @test
     */
    public function test_get_departments_users_list(): void
    {
        $this->authenticateWithPermission(UserPermission::fromValue(UserPermission::VIEW_DEPARTMENT_USERS));

        $this->brand->makeCurrent();

        $departments = Department::factory()->count(2)->create(static::demoData());

        $this->user->departments()->syncWithoutDetaching($departments);

        for ($i = 1; $i <= 2; $i++) {
            $department = $departments->skip($i - 1)->first();

            $userFactory = User::factory()->count($i)->create();

            $this->brand->makeCurrent();

            $department->users()->syncWithoutDetaching($userFactory);
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

        $this->brand->makeCurrent();

        $departments = Department::factory(2)->create();
        $this->user->departments()->syncWithoutDetaching($departments);

        for ($i = 1; $i <= 2; $i++) {
            $department = $departments->skip($i - 1)->first();

            $userFactory = User::factory($i)->create();

            $this->brand->makeCurrent();

            $department->users()->syncWithoutDetaching($userFactory);
        }

        $anotherUser = User::factory()->create();

        $this->brand->makeCurrent();

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
        $this->brand->makeCurrent();

        $departments = Department::factory(2)->create();
        $this->user?->departments()->syncWithoutDetaching($departments);

        for ($i = 1; $i <= 2; $i++) {
            $department = $departments->skip($i - 1)->first();

            $userFactory = User::factory($i)->create();

            $this->brand->makeCurrent();

            $department->users()->syncWithoutDetaching($userFactory);
        }

        $response = $this->get(route('admin.departments.users.allByDepartments'));

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
