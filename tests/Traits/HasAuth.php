<?php

declare(strict_types=1);

namespace Tests\Traits;

use Modules\Role\Contracts\PermissionEnum;
use Modules\Role\Enums\DefaultRole;
use Modules\Role\Models\Permission;
use Modules\Role\Models\Role;
use Modules\User\Models\User;
use Tests\CreatesApplication;

trait HasAuth
{
    use CreatesApplication;

    /**
     * @var User|null
     */
    protected ?User $user = null;

    /**
     * Authenticate user.
     *
     * @param  string  $guard
     * @return void
     */
    final protected function authenticateUser(string $guard = User::DEFAULT_AUTH_GUARD): void
    {
        $user = User::whereEmail('user@service.com')->first() ??
            User::factory()->create([
                'email' => 'user@service.com',
            ]);

        $this->setUser($user);

        $this->actingAs($user, $guard);
    }

    /**
     * Authenticate admin.
     *
     * @param  string  $guard
     * @return void
     */
    final protected function authenticateAdmin(string $guard = User::DEFAULT_AUTH_GUARD): void
    {
        $user = User::whereEmail('admin@service.com')->first() ??
            User::factory()->create([
                'email' => 'admin@service.com',
            ]);

        $role = Role::factory()->create([
            'name' => DefaultRole::ADMIN,
        ]);

        $user->roles()->sync($role);

        $this->setUser($user);

        $this->actingAs($user, User::DEFAULT_AUTH_GUARD);
    }

    /**
     * Authenticate user with permission.
     *
     * @param  PermissionEnum  $permissionEnum
     * @param  string  $guard
     * @return void
     */
    final protected function authenticateWithPermission(
        PermissionEnum $permissionEnum,
        string $guard = User::DEFAULT_AUTH_GUARD
    ): void {
        $user = User::whereEmail('test@service.com')->first() ??
            User::factory()->create([
                'email' => 'test@service.com',
            ]);

        $permission = Permission::whereName($permissionEnum->value)->first() ??
            Permission::factory()->create([
                'name' => $permissionEnum->value,
            ]);

        $user->givePermissionTo($permission);

        $this->setUser($user);

        $this->actingAs($user, $guard);
    }

    /**
     * Set user.
     *
     * @param  User  $user
     * @return $this
     */
    final protected function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return User|null
     */
    final protected function getUser(): ?User
    {
        return $this->user;
    }
}
