<?php

declare(strict_types=1);

namespace Modules\Role\Policies;

use App\Policies\BasePolicy;
use Modules\Role\Enums\RolePermission;
use Modules\Role\Models\Role;
use Modules\User\Models\User;
use Spatie\Multitenancy\Models\Tenant;

final class RolePolicy extends BasePolicy
{
    /**
     * View any policy.
     *
     * @param  User  $user
     * @return bool
     */
    final public function viewAny(User $user): bool
    {
        return $user->can(RolePermission::VIEW_ROLES);
    }

    /**
     * View policy.
     *
     * @param  User  $user
     * @param  Role  $role
     * @return bool
     */
    final public function view(User $user, Role $role): bool
    {
        return match (true) {
            $user->isAdmin() => true,
            $user->can(RolePermission::VIEW_ROLES) && $role->brand => $role->brand_id == Tenant::current()?->id,
            $user->can(RolePermission::VIEW_ROLES) => true,
            default => false,
        };
    }

    /**
     * Create role policy.
     *
     * @param  User  $user
     * @return bool
     */
    final public function create(User $user): bool
    {
        return $user->can(RolePermission::CREATE_ROLES);
    }

    /**
     * Update role policy.
     *
     * @param  User  $user
     * @param  Role  $role
     * @return bool
     */
    final public function update(User $user, Role $role): bool
    {
        return match (true) {
            $user->isAdmin() => true,
            $user->can(RolePermission::EDIT_ROLES) && $role->brand => $role->brand_id == Tenant::current()?->id,
            $user->can(RolePermission::EDIT_ROLES) => true,
            default => false,
        };
    }

    /**
     * Delete role policy.
     *
     * @param  User  $user
     * @param  Role  $role
     * @return bool
     */
    final public function delete(User $user, Role $role): bool
    {
        return match (true) {
            $user->isAdmin() => true,
            $user->can(RolePermission::DELETE_ROLES) && $role->brand => $role->brand_id == Tenant::current()?->id,
            $user->can(RolePermission::DELETE_ROLES) => true,
            default => false,
        };
    }
}
