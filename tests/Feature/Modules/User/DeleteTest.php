<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\User;

use Modules\Brand\Models\Brand;
use Modules\User\Enums\UserPermission;
use Modules\User\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

final class DeleteTest extends TestCase
{
    /**
     * @test
     */
    public function admin_can_delete(): void
    {
        $this->authenticateAdmin();

        $user = User::factory()->create();
        $response = $this->delete(route('admin.users.destroy', ['worker' => $user]));

        $response->assertNoContent();
        $this->assertSoftDeleted($user);
    }

    /**
     * @test
     */
    public function user_with_permission_can_delete(): void
    {
        $this->authenticateWithPermissions([
            UserPermission::fromValue(UserPermission::DELETE_USERS),
            UserPermission::fromValue(UserPermission::VIEW_USERS),
        ]);

        $user = User::factory()->create();
        $response = $this->delete(route('admin.users.destroy', ['worker' => $user]));

        $response->assertNoContent();
        $this->assertSoftDeleted($user);
    }

    /**
     * @test
     */
    public function user_with_brand_can_delete(): void
    {
        $this->authenticateUser();

        Brand::factory()
            ->create()
            ->users()
            ->sync($users = User::factory(1)->create()->push($this->user));

        $response = $this->delete(route('admin.users.destroy', ['worker' => $users->first()]));

        $response->assertNoContent();
        $this->assertSoftDeleted($users->first());
    }

    /**
     * @test
     */
    public function user_can_delete_not_found(): void
    {
        $this->authenticateAdmin();

        $userId = User::orderByDesc('id')->first()?->id + 1 ?? 1;

        $response = $this->delete(route('admin.users.destroy', ['worker' => $userId]));

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function can_not_delete(): void
    {
        $this->authenticateUser();

        $user = User::factory()->create();
        $response = $this->delete(route('admin.users.destroy', ['worker' => $user]));

        $response->assertServerError();
    }

    /**
     * @test
     */
    public function not_unauthorized(): void
    {
        $user = User::factory()->create();
        $response = $this->delete(route('admin.users.destroy', ['worker' => $user]));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
