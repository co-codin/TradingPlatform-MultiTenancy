<?php

namespace Modules\User;

use Modules\Role\Enums\DefaultRole;
use Modules\Role\Models\Role;
use Modules\User\Enums\UserPermission;
use Modules\User\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

final class UpdateTest extends TestCase
{
    public function test_user_can_update(): void
    {
        $this->authenticateWithPermission(UserPermission::fromValue(UserPermission::EDIT_USERS));

        $user = User::factory()->create();
        $response = $this->put("/admin/users/$user->id", [
            'username' => fake()->userName,
            'first_name' => fake()->firstName,
            'last_name' => fake()->lastName,
            'email' => fake()->email,
            'is_active' => fake()->boolean,
            'parent_id' => User::all()->random()->id,
            'target' => fake()->randomNumber(),
            'change_password' => true,
            'password' => 'admin',
            'password_confirmation' => 'admin',
            'role_id' => [
                Role::factory()->create([
                    'name' => DefaultRole::ADMIN,
                ])->id,
            ],
        ]);

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

    public function test_user_can_update_not_found(): void
    {
        $this->authenticateWithPermission(UserPermission::fromValue(UserPermission::EDIT_USERS));

        $response = $this->put("/admin/users/10", [
            'username' => fake()->userName,
            'first_name' => fake()->firstName,
            'last_name' => fake()->lastName,
            'email' => fake()->email,
            'is_active' => fake()->boolean,
            'parent_id' => User::all()->random()->id,
            'target' => fake()->randomNumber(),
            'change_password' => true,
            'password' => 'admin',
            'password_confirmation' => 'admin',
            'role_id' => [
                Role::factory()->create([
                    'name' => DefaultRole::ADMIN,
                ])->id,
            ],
        ]);

        $response->assertNotFound();
    }

    public function test_can_not_update(): void
    {
        $this->authenticateUser();

        $user = User::factory()->create();
        $response = $this->put("/admin/users/$user->id", [
            'username' => fake()->userName,
            'first_name' => fake()->firstName,
            'last_name' => fake()->lastName,
            'email' => fake()->email,
            'is_active' => fake()->boolean,
            'parent_id' => User::all()->random()->id,
            'target' => fake()->randomNumber(),
            'password' => 'admin',
            'password_confirmation' => 'admin',
            'role_id' => [
                Role::factory()->create([
                    'name' => DefaultRole::ADMIN,
                ])->id,
            ],
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_not_unauthorized(): void
    {
        $response = $this->put('/admin/users');

        $response->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED);
    }
}
