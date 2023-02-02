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
    protected function setUp(): void
    {
        parent::setUp();
        $this->app->make(PermissionRegistrar::class)->registerPermissions();
    }

    /**
     * @test
     */
    public function can_create(): void
    {
        $this->authenticateWithPermission(UserPermission::fromValue(UserPermission::CREATE_USERS));

        $response = $this->post('/admin/workers', array_merge(
            User::factory()->withParent()
                ->withAffiliate()
                ->raw(['password' => self::$basePassword, 'is_active' => fake()->boolean]),
            [
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
                ->raw(['password' => self::$basePassword, 'is_active' => fake()->boolean]),
            [
                'roles' => [
                    [
                        'id' => Role::factory()->create()->id,
                    ],
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
}
