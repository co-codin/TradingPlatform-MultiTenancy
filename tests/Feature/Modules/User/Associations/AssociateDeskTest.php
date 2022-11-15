<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\User\Associations;

use Modules\Desk\Models\Desk;
use Modules\User\Enums\UserPermission;
use Modules\User\Models\User;
use Tests\TestCase;

final class AssociateDeskTest extends TestCase
{
    /**
     * @test
     */
    public function user_can_update(): void
    {
        $this->authenticateWithPermission(UserPermission::fromValue(UserPermission::EDIT_USERS));

        $user = User::factory()->create();
        $response = $this->put("/admin/users/$user->id/desk", [
            'desks' => [
                Desk::factory()->create(),
                Desk::factory()->create(),
            ],
        ]);

        $response->assertOk();
    }

    /**
     * @test
     */
    public function user_can_update_not_found(): void
    {
        $this->authenticateWithPermission(UserPermission::fromValue(UserPermission::EDIT_USERS));

        $response = $this->put('/admin/users/10/desk', [
            'desks' => [
                Desk::factory()->create(),
            ],
        ]);

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function can_not_update(): void
    {
        $this->authenticateUser();

        $user = User::factory()->create();
        $response = $this->put("/admin/users/$user->id/desk", [
            'desks' => [
                Desk::factory()->create(),
            ],
        ]);

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_unauthorized(): void
    {
        $response = $this->put('/admin/users/1/desk');

        $response->assertUnauthorized();
    }
}
