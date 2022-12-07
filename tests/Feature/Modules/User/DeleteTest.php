<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\User;

use Modules\Brand\Models\Brand;
use Modules\User\Enums\UserPermission;
use Modules\User\Models\User;
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
    public function user_with_brand_and_permission_can_delete(): void
    {
        $this->authenticateWithPermission(UserPermission::fromValue(UserPermission::DELETE_USERS));

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
    public function user_from_other_brand_cant_delete(): void
    {
        $this->authenticateWithPermission(UserPermission::fromValue(UserPermission::DELETE_USERS));

        Brand::factory()
            ->create()
            ->users()
            ->sync($user = User::factory()->create());

        $response = $this->delete(route('admin.users.destroy', ['worker' => $user]));

        $response->assertForbidden();
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

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_unauthorized(): void
    {
        $user = User::factory()->create();
        $response = $this->delete(route('admin.users.destroy', ['worker' => $user]));

        $response->assertUnauthorized();
    }
}
