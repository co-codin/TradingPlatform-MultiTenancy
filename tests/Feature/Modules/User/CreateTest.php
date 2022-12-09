<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\User;

use Modules\Role\Models\Role;
use Modules\User\Enums\UserPermission;
use Modules\User\Models\User;
use Spatie\Permission\PermissionRegistrar;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

final class CreateTest extends TestCase
{
    /**
     * @test
     */
    public function can_create(): void
    {
        $this->authenticateWithPermission(UserPermission::fromValue(UserPermission::CREATE_USERS));

        $response = $this->post('/admin/workers', array_merge(
            User::factory()->withParent()
                ->withAffiliate()
                ->raw(['password' => 'admin', 'is_active' => fake()->boolean]),
            [
                'password_confirmation' => 'admin',
                'roles' => [
                    [
                        'id' => Role::factory()->create()->id,
                    ],
                ],
            ]
        ));

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

    /**
     * @test
     */
    public function can_not_create(): void
    {
        $this->authenticateUser();

        $response = $this->post('/admin/workers', array_merge(
            User::factory()
                ->withParent()
                ->withAffiliate()
                ->raw(['password' => 'admin', 'is_active' => fake()->boolean]),
            [
                'password_confirmation' => 'admin',
                'role_id' => [
                    Role::factory()->create()->id,
                ],
            ]
        ));

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     */
    public function not_unauthorized(): void
    {
        $response = $this->post('/admin/workers');

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->app->make(PermissionRegistrar::class)->registerPermissions();
    }
}
