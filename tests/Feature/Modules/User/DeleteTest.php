<?php

namespace Modules\User;

use Modules\User\Enums\UserPermission;
use Modules\User\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

final class DeleteTest extends TestCase
{
    public function test_user_can_delete(): void
    {
        $this->authenticateWithPermission(UserPermission::fromValue(UserPermission::DELETE_USERS));

        $user = User::factory()->create();
        $response = $this->delete("/admin/users/$user->id");

        $response->assertNoContent();
        $this->assertSoftDeleted($user);
    }

    public function test_user_can_delete_not_found(): void
    {
        $this->authenticateWithPermission(UserPermission::fromValue(UserPermission::DELETE_USERS));

        $response = $this->delete("/admin/users/10");

        $response->assertNotFound();
    }

    public function test_can_not_delete(): void
    {
        $this->authenticateUser();

        $user = User::factory()->create();
        $response = $this->delete("/admin/users/$user->id");

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_not_unauthorized(): void
    {
        $user = User::factory()->create();
        $response = $this->delete("/admin/users/$user->id");

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
