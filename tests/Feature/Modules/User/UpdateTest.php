<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\User;

use Modules\Brand\Models\Brand;
use Modules\Role\Enums\DefaultRole;
use Modules\Role\Models\Role;
use Modules\User\Enums\UserPermission;
use Modules\User\Models\User;
use Tests\TestCase;

final class UpdateTest extends TestCase
{
    /**
     * @test
     */
    public function admin_can_update(): void
    {
        $this->authenticateAdmin();

        $user = User::factory()->create();

        $response = $this->patch(route('admin.users.update', ['worker' => $user]), array_merge(
            User::factory()->withParent()->raw(['password' => 'admin', 'is_active' => fake()->boolean]),
            [
                'change_password' => true,
                'password_confirmation' => 'admin',
                'roles' => [
                    [
                        'id' => (Role::first() ?? Role::factory()->create([
                            'name' => DefaultRole::ADMIN,
                        ]))->id,
                    ],
                ],
            ]
        ));

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                'id',
                'username',
                'first_name',
                'last_name',
                'email',
                'is_active',
                'target',
                '_lft',
                '_rgt',
                'parent_id',
                'deleted_at',
                'last_login',
                'created_at',
                'roles',
            ],
        ]);
    }

    /**
     * @test
     */
    public function user_with_permission_can_update(): void
    {
        $this->authenticateWithPermissions([
            UserPermission::fromValue(UserPermission::VIEW_USERS),
            UserPermission::fromValue(UserPermission::EDIT_USERS),
        ]);

        $user = User::factory()->create();

        $response = $this->patch(route('admin.users.update', ['worker' => $user]), array_merge(
            User::factory()->withParent()->raw(['password' => 'admin', 'is_active' => fake()->boolean]),
            [
                'change_password' => true,
                'password_confirmation' => 'admin',
                'roles' => [
                    [
                        'id' => (Role::first() ?? Role::factory()->create([
                            'name' => DefaultRole::ADMIN,
                        ]))->id,
                    ],
                ],
            ]
        ));

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                'id',
                'username',
                'first_name',
                'last_name',
                'email',
                'is_active',
                'target',
                '_lft',
                '_rgt',
                'parent_id',
                'deleted_at',
                'last_login',
                'created_at',
                'roles',
            ],
        ]);
    }

    /**
     * @test
     */
    public function user_with_brand_can_update(): void
    {
        $this->authenticateUser();

        Brand::factory()
            ->create()
            ->users()
            ->sync($users = User::factory(1)->create()->push($this->user));

        $response = $this->patch(route('admin.users.update', ['worker' => $users->first()]), array_merge(
            User::factory()->withParent()->raw(['password' => 'admin', 'is_active' => fake()->boolean]),
            [
                'change_password' => true,
                'password_confirmation' => 'admin',
                'roles' => [
                    [
                        'id' => (Role::first() ?? Role::factory()->create([
                            'name' => DefaultRole::ADMIN,
                        ]))->id,
                    ],
                ],
            ]
        ));

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                'id',
                'username',
                'first_name',
                'last_name',
                'email',
                'is_active',
                'target',
                '_lft',
                '_rgt',
                'parent_id',
                'deleted_at',
                'last_login',
                'created_at',
                'roles',
            ],
        ]);
    }

    /**
     * @test
     */
    public function not_found(): void
    {
        $this->authenticateAdmin();

        $userId = User::orderByDesc('id')->first()?->id + 1 ?? 1;

        $response = $this->patch(route('admin.users.update', ['worker' => $userId]), array_merge(
            User::factory()->withParent()->raw(['password' => 'admin', 'is_active' => fake()->boolean]),
            [
                'change_password' => true,
                'password_confirmation' => 'admin',
                'roles' => [
                    [
                        'id' => (Role::first() ?? Role::factory()->create([
                            'name' => DefaultRole::ADMIN,
                        ]))->id,
                    ],
                ],
            ]
        ));

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function can_not_update(): void
    {
        $this->authenticateUser();

        $user = User::factory()->create();

        $response = $this->patch(route('admin.users.update', ['worker' => $user]), array_merge(
            User::factory()->withParent()->raw(['password' => 'admin', 'is_active' => fake()->boolean]),
            [
                'password_confirmation' => 'admin',
                'roles' => [
                    [
                        'id' => (Role::first() ?? Role::factory()->create([
                            'name' => DefaultRole::ADMIN,
                        ]))->id,
                    ],
                ],
            ]
        ));

        $response->assertServerError();
    }

    /**
     * @test
     */
    public function unauthorized(): void
    {
        $user = User::factory()->create();

        $response = $this->patch(route('admin.users.update', ['worker' => $user]));

        $response->assertUnauthorized();
    }
}
