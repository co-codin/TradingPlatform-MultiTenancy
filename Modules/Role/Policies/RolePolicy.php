<?php

declare(strict_types=1);

namespace Modules\Role\Policies;

use App\Policies\BasePolicy;
use Illuminate\Database\Eloquent\Model;
use Modules\Role\Enums\RolePermission;
use Modules\Role\Models\Role;
use Modules\User\Models\User;

final class RolePolicy extends BasePolicy
{
    /**
     * View any policy.
     *
     * @param User $user
     * @return bool
     */
    final public function viewAny(User $user): bool
    {
        return $user->can(RolePermission::VIEW_ROLES);
    }

    /**
     * View policy.
     *
     * @param User $user
     * @param Model $model
     * @return bool
     */
    final public function view(User $user, Model $model): bool
    {
        return $user->can(RolePermission::VIEW_ROLES);
    }

    /**
     * Create role policy.
     *
     * @param User $user
     * @return bool
     */
    final public function create(User $user): bool
    {
        return $user->can(RolePermission::CREATE_ROLES);
    }

    /**
     * Update role policy.
     *
     * @param User $user
     * @param Role $role
     * @return bool
     */
    final public function update(User $user, Role $role): bool
    {
        return $user->can(RolePermission::EDIT_ROLES);
    }

    /**
     * Delete role policy.
     *
     * @param User $user
     * @param Role $role
     * @return bool
     */
    final public function delete(User $user, Role $role): bool
    {
        return $user->can(RolePermission::DELETE_ROLES);
    }
}
