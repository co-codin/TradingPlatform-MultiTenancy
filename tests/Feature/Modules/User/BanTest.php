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
     * @test
     */
    public function authorized_user_can_ban_user()
    {
    }

    /**
     * @test
     */
    public function unauthorized_user_cant_ban_user()
    {
    }

    /**
     * @test
     */
    public function authorized_user_can_unban_user()
    {
    }

    /**
     * @test
     */
    public function unauthorized_user_cant_unban_user()
    {
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
