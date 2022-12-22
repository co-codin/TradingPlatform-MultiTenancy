<?php

declare(strict_types=1);

namespace Tests\Traits;

use Illuminate\Support\Arr;
use Modules\Role\Contracts\PermissionEnum;
use Modules\Role\Enums\DefaultRole;
use Modules\Role\Models\Permission;
use Modules\Role\Models\Role;
use Modules\User\Models\User;

trait HasAuth
{
    protected readonly User $user;

    /**
     * Authenticate user.
     *
     * @param  string  $guard
     * @return void
     */
    final protected function authenticateUser(string $guard = User::DEFAULT_AUTH_GUARD): void
    {
        $email = 'user@service.com';

        $user = User::whereEmail($email)->first() ??
            User::factory()->create([
                'email' => $email,
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
        $email = 'admin@service.com';

        $user = User::whereEmail($email)->first() ??
            User::factory()->create([
                'email' => $email,
            ]);

        $role = Role::where('name', DefaultRole::ADMIN)->first() ??
            Role::factory()->create([
                'name' => DefaultRole::ADMIN,
            ]);

        $user->roles()->sync($role);

        $this->setUser($user);

        $this->actingAs($user, $guard);
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
        $email = 'test@service.com';

        /** @var User $user */
        $user = User::whereEmail($email)->first() ??
            User::factory()->create([
                'email' => $email,
            ]);

        $permission = Permission::whereName($permissionEnum->value)->first() ??
            Permission::factory()->create([
                'name' => $permissionEnum->value,
                'guard_name' => $guard,
            ]);

        $user->permissions()->syncWithoutDetaching($permission);

        $this->setUser($user);

        $this->actingAs($user, $guard);
    }

    final protected function addPermissions(array $permissionEnums): void
    {
        $user = $this->getUser();
        $permissions = [];

        foreach ($permissionEnums as $enum) {
            $permissions[] = Permission::whereName($enum->value)->first() ?? Permission::factory()->create([
                'name' => $enum->value,
            ]);
        }

        $user->permissions()->syncWithoutDetaching(Arr::pluck($permissions, 'id'));
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
     */
    final protected function getUser(): User
    {
        return $this->user;
    }
}
