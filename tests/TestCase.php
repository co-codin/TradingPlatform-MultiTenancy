<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Modules\Role\Contracts\PermissionEnum;
use Modules\Role\Enums\DefaultRole;
use Modules\Role\Models\Permission;
use Modules\Role\Models\Role;
use Modules\User\Models\User;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    final protected function authenticateUser(): void
    {
        $this->actingAs(User::factory()->create([
            'email' => 'test@service.com',
        ]), User::DEFAULT_AUTH_GUARD);
    }

    final protected function authenticateAdmin(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@service.com',
        ]);

        $role = Role::factory()->create([
            'name' => DefaultRole::ADMIN,
        ]);

        $user->roles()->sync($role);

        $this->actingAs($user, User::DEFAULT_AUTH_GUARD);
    }

    final protected function authenticateWithPermission(PermissionEnum $permissionEnum): void
    {
        $user = User::factory()->create([
            'email' => 'test@service.com',
        ]);

        $permission = Permission::factory()->create([
            'name' => $permissionEnum->value,
        ]);

        $user->givePermissionTo($permission->name);

        $this->actingAs($user, User::DEFAULT_AUTH_GUARD);
    }
}
