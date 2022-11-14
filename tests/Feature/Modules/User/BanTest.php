<?php

namespace Tests\Feature\Modules\User;

use Modules\Role\Models\Permission;
use Modules\User\Enums\UserPermission;
use Modules\User\Models\User;
use Tests\TestCase;

class BanTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => 'admin@admin.com'
        ])
            ->givePermissionTo(Permission::factory()->create([
                'name' => UserPermission::BAN_USERS,
            ])?->name);
    }

    public function test_authorized_user_can_ban_user()
    {

    }

    public function test_unauthorized_user_cant_ban_user()
    {

    }

    public function test_authorized_user_can_unban_user()
    {

    }

    public function test_unauthorized_user_cant_unban_user()
    {

    }
}
