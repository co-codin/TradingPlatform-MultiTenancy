<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\User\Associations;

use Modules\Brand\Models\Brand;
use Modules\User\Enums\UserPermission;
use Modules\User\Models\User;
use Tests\TestCase;

final class AssociateBrandTest extends TestCase
{
    /**
     * @test
     */
    public function user_can_update(): void
    {
        $this->authenticateWithPermission(UserPermission::fromValue(UserPermission::EDIT_USERS));

        $user = User::factory()->create();
        $response = $this->put("/admin/workers/$user->id/brand", [
            'brands' => [
                Brand::factory()->create(),
                Brand::factory()->create(),
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

        $response = $this->put('/admin/workers/10/brand', [
            'brands' => [
                Brand::factory()->create(),
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
        $response = $this->put("/admin/workers/$user->id/brand", [
            'brands' => [
                Brand::factory()->create(),
            ],
        ]);

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_unauthorized(): void
    {
        $response = $this->put('/admin/workers/1/brand');

        $response->assertUnauthorized();
    }
}
