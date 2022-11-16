<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\User;

use Modules\User\Enums\UserPermission;
use Modules\User\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

final class DeleteTest extends TestCase
{
    /**
     * @test
     */
    public function user_can_delete(): void
    {
        $this->authenticateWithPermission(UserPermission::fromValue(UserPermission::DELETE_USERS));

        $user = User::factory()->create();
        $response = $this->delete("/admin/workers/$user->id");

        $response->assertNoContent();
        $this->assertSoftDeleted($user);
    }

    /**
     * @test
     */
    public function user_can_delete_not_found(): void
    {
        $this->authenticateWithPermission(UserPermission::fromValue(UserPermission::DELETE_USERS));

        $response = $this->delete('/admin/workers/1000');

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function can_not_delete(): void
    {
        $this->authenticateUser();

        $user = User::factory()->create();
        $response = $this->delete("/admin/workers/$user->id");

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     */
    public function not_unauthorized(): void
    {
        $user = User::factory()->create();
        $response = $this->delete("/admin/workers/$user->id");

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
