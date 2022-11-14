<?php

declare(strict_types=1);

namespace Modules\User;

use Modules\User\Enums\UserPermission;
use Modules\User\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

final class ReadTest extends TestCase
{
    public function test_user_can_view_any(): void
    {
        User::factory($count = 5)->create();

        $this->authenticateWithPermission(UserPermission::fromValue(UserPermission::VIEW_USERS));
        $response = $this->get('/admin/users');

        $response->assertOk();
        $this->assertCount(++$count, $response['data']);

        $response->assertJsonStructure([
            'data' => [
                [
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
                    //'roles',
                    //'permissions',
                ],
            ],
        ]);
    }

    public function test_user_can_view(): void
    {
        $this->authenticateWithPermission(UserPermission::fromValue(UserPermission::VIEW_USERS));

        $user = User::factory()->create();
        $response = $this->get("/admin/users/{$user->id}");

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
                //'roles',
                //'permissions',
            ],
        ]);
    }

    public function test_user_can_view_not_found(): void
    {
        $this->authenticateWithPermission(UserPermission::fromValue(UserPermission::VIEW_USERS));

        $response = $this->get("/admin/users/10");

        $response->assertNotFound();
    }

    public function test_can_not_view_any(): void
    {
        $this->authenticateUser();
        $response = $this->get('/admin/users');

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_can_not_view(): void
    {
        $user = User::factory()->create();

        $this->authenticateUser();
        $response = $this->get("/admin/users/{$user->id}");

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_not_unauthorized_view_any(): void
    {
        $response = $this->get('/admin/users');

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_not_unauthorized_view(): void
    {
        $user = User::factory()->create();
        $response = $this->get("/admin/users/{$user->id}");

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
