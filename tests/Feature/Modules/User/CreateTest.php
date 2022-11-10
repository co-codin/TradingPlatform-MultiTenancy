<?php

namespace Modules\User;

use Modules\Role\Enums\DefaultRole;
use Modules\Role\Models\Role;
use Modules\User\Enums\UserPermission;
use Modules\User\Models\User;
use Spatie\Permission\PermissionRegistrar;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

final class CreateTest extends TestCase
{
    public function test_can_create(): void
    {
        $this->authenticateWithPermission(UserPermission::fromValue(UserPermission::CREATE_USERS));

        $response = $this->post("/admin/users", [
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

        $response->assertCreated();
        $response->assertJsonStructure([
            'data' => [
                'id',
                'username',
                'first_name',
                'last_name',
                'email',
                'is_active',
                'target',
                'parent_id',
                'created_at',
                'updated_at',
                'roles',
            ],
        ]);
    }

    public function test_can_not_create(): void
    {
        $this->authenticateUser();

        $response = $this->post("/admin/users", [
            'username' => fake()->userName,
            'first_name' => fake()->firstName,
            'last_name' => fake()->lastName,
            'email' => fake()->email,
            'is_active' => fake()->boolean,
            'parent_id' => User::all()->random()->id,
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
        $response = $this->post('/admin/users', User::factory()->raw());

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->app->make(PermissionRegistrar::class)->registerPermissions();
    }
}
