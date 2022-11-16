<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\User;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Role\Enums\DefaultRole;
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
                'roles' => [
                    Role::factory()->create([
                        'name' => DefaultRole::ADMIN,
                    ])->toArray(),
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
}
