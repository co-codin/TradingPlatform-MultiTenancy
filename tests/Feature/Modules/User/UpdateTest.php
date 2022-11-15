<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\User;

use Exception;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Role\Enums\DefaultRole;
use Modules\Role\Models\Permission;
use Modules\Role\Models\Role;
use Modules\User\Enums\UserPermission;
use Modules\User\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

final class UpdateTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function user_can_update(): void
    {
        $this->authenticateWithPermission(UserPermission::fromValue(UserPermission::EDIT_USERS));

        $user = User::factory()->create();
        $response = $this->put("/admin/users/$user->id", array_merge(
            User::factory()->raw(['password' => 'admin', 'is_active' => fake()->boolean]),
            [
                'parent_id' => User::inRandomOrder()->first()->id,
                'change_password' => true,
                'password_confirmation' => 'admin',
                'role_id' => [
                    Role::factory()->create([
                        'name' => DefaultRole::ADMIN,
                    ])->id,
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
    public function user_can_update_not_found(): void
    {
        $this->authenticateWithPermission(UserPermission::fromValue(UserPermission::EDIT_USERS));

        $response = $this->put('/admin/users/10', array_merge(
            User::factory()->raw(['password' => 'admin', 'is_active' => fake()->boolean]),
            [
                'parent_id' => User::all()->random()->id,
                'change_password' => true,
                'password_confirmation' => 'admin',
                'role_id' => [
                    Role::factory()->create([
                        'name' => DefaultRole::ADMIN,
                    ])->id,
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
        $response = $this->put("/admin/users/$user->id", array_merge(
            User::factory()->raw(['password' => 'admin', 'is_active' => fake()->boolean]),
            [
                'parent_id' => User::all()->random()->id,
                'password_confirmation' => 'admin',
                'role_id' => [
                    Role::factory()->create([
                        'name' => DefaultRole::ADMIN,
                    ])->id,
                ],
            ]
        ));

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_unauthorized(): void
    {
        $response = $this->put('/admin/users');

        $response->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED);
    }

    /**
     * Test authorized user can update batch.
     *
     * @test
     *
     * @return void
     *
     * @throws Exception
     */
    public function authorized_user_can_update_patch(): void
    {
        $authUserWithPermission = User::factory()->create()
            ->givePermissionTo(Permission::factory()->create([
                'name' => UserPermission::EDIT_USERS,
            ]));

        $users = User::factory(3)->create();

        $data['users'] = [
            [
                'id' => $users[0]['id'],
                'username' => 'Tester',
                'first_name' => 'Test',
                'last_name' => 'Testovich',
            ],
            [
                'id' => $users[1]['id'],
                'email' => 'test@test.com',
            ],
            [
                'id' => $users[2]['id'],
                'is_active' => random_int(0, 1),
                'target' => random_int(1, 100),
                'parent_id' => User::inRandomOrder()->first()?->id,
            ],
        ];

        $response = $this->actingAs($authUserWithPermission, User::DEFAULT_AUTH_GUARD)
            ->patchJson(
                route('admin.users.update.batch', ['user' => $authUserWithPermission->id]),
                $data,
            );

        $response->assertOk();

        $response->assertJson([
            'data' => $data['users'],
        ]);
    }

    /**
     * Test unauthorized user cant update batch.
     *
     * @test
     *
     * @return void
     *
     * @throws Exception
     */
    public function unauthorized_user_cant_update_patch(): void
    {
        $user = User::factory()->create();

        $users = User::factory(3)->create();

        $data['users'] = [
            [
                'id' => $users[0]['id'],
                'username' => 'Tester',
                'first_name' => 'Test',
                'last_name' => 'Testovich',
            ],
            [
                'id' => $users[1]['id'],
                'email' => 'test@test.com',
            ],
            [
                'id' => $users[2]['id'],
                'is_active' => random_int(0, 1),
                'target' => random_int(1, 100),
                'parent_id' => User::inRandomOrder()->first()?->id,
            ],
        ];

        $response = $this->patchJson(
            route('admin.users.update.batch', ['user' => $user->id]),
            $data,
        );

        $response->assertUnauthorized();
    }

    /**
     * Test authorized user without permissions cant update batch.
     *
     * @test
     *
     * @return void
     *
     * @throws Exception
     */
    public function authorized_user_without_permissions_cant_update_patch(): void
    {
        $authUserWithoutPermission = User::factory()->create();

        $users = User::factory(3)->create();

        $data['users'] = [
            [
                'id' => $users[0]['id'],
                'username' => 'Tester',
                'first_name' => 'Test',
                'last_name' => 'Testovich',
            ],
            [
                'id' => $users[1]['id'],
                'email' => 'test@test.com',
            ],
            [
                'id' => $users[2]['id'],
                'is_active' => random_int(0, 1),
                'target' => random_int(1, 100),
                'parent_id' => User::inRandomOrder()->first()?->id,
            ],
        ];

        $response = $this->actingAs($authUserWithoutPermission)
            ->patchJson(
                route('admin.users.update.batch', ['user' => $authUserWithoutPermission->id]),
                $data,
            );

        $response->assertUnauthorized();
    }
}
