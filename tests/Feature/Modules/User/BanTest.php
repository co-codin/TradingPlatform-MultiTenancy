<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\User;

use Modules\Role\Models\Permission;
use Modules\User\Enums\UserPermission;
use Modules\User\Models\User;
use Tests\TestCase;

final class BanTest extends TestCase
{
    /**
     * Test authorized user can ban.
     *
     * @test
     *
     * @return void
     */
    public function authorized_user_can_ban_user(): void
    {
        $userData = User::factory(3)->create();
        $userIds = [];

        foreach ($userData as $user) {
            $userIds[] = ['id' => $user->id];
        }

        $response = $this->actingAs($this->user, User::DEFAULT_AUTH_GUARD)
            ->patchJson(
                route('admin.users.ban', ['user' => $this->user->id]),
                ['users' => $userIds],
            );

        $response->assertOk();

        $response->assertJson([
            'data' => $userIds,
        ]);
    }

    /**
     * Test authorized user without permission cant ban.
     *
     * @test
     *
     * @return void
     */
    public function authorized_user_without_permission_cant_ban_user(): void
    {
        $userData = User::factory(3)->create();
        $userIds = [];

        foreach ($userData as $user) {
            $userIds[] = ['id' => $user->id];
        }

        $response = $this->actingAs(User::factory()->create(), User::DEFAULT_AUTH_GUARD)
            ->patchJson(
                route('admin.users.ban', ['user' => $this->user->id]),
                ['users' => $userIds],
            );

        $response->assertForbidden();
    }

    /**
     * Test unauthorized user cant ban.
     *
     * @test
     *
     * @return void
     */
    public function unauthorized_user_cant_ban_user(): void
    {
        $userData = User::factory(3)->create();
        $userIds = [];

        foreach ($userData as $user) {
            $userIds[] = ['id' => $user->id];
        }

        $response = $this->patchJson(
            route('admin.users.ban', ['user' => $this->user->id]),
            ['users' => $userIds],
        );

        $response->assertUnauthorized();
    }

    /**
     * Test authorized user can unban.
     *
     * @test
     *
     * @return void
     */
    public function authorized_user_can_unban_user(): void
    {
        $userData = User::factory(3)->create();
        $userIds = [];

        foreach ($userData as $user) {
            $userIds[] = ['id' => $user->id];
        }

        $response = $this->actingAs($this->user, User::DEFAULT_AUTH_GUARD)
            ->patchJson(
                route('admin.users.unban', ['user' => $this->user->id]),
                ['users' => $userIds],
            );

        $response->assertOk();

        $response->assertJson([
            'data' => $userIds,
        ]);
    }

    /**
     * Test authorized user without permission cant unban.
     *
     * @test
     *
     * @return void
     */
    public function authorized_user_without_permission_cant_unban_user(): void
    {
        $userData = User::factory(3)->create();
        $userIds = [];

        foreach ($userData as $user) {
            $userIds[] = ['id' => $user->id];
        }

        $response = $this->actingAs(User::factory()->create(), User::DEFAULT_AUTH_GUARD)
            ->patchJson(
                route('admin.users.unban', ['user' => $this->user->id]),
                ['users' => $userIds],
            );

        $response->assertForbidden();
    }

    /**
     * Test unauthorized user cant unban.
     *
     * @test
     *
     * @return void
     */
    public function unauthorized_user_cant_unban_user(): void
    {
        $userData = User::factory(3)->create();
        $userIds = [];

        foreach ($userData as $user) {
            $userIds[] = ['id' => $user->id];
        }

        $response = $this->patchJson(
            route('admin.users.unban', ['user' => $this->user->id]),
            ['users' => $userIds],
        );

        $response->assertUnauthorized();
    }

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create()
            ->givePermissionTo(Permission::factory()->create([
                'name' => UserPermission::BAN_USERS,
            ]));
    }
}
