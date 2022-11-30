<?php

declare(strict_types=1);

namespace Modules\Role\Policies;

use App\Policies\BasePolicy;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Role\Enums\PermissionPermission;
use Modules\Role\Models\Permission;
use Modules\User\Models\User;

final class PermissionPolicy extends BasePolicy
{
    use HandlesAuthorization;

    /**
     * View any permission`s policy.
     *
     * @param  User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->can(PermissionPermission::VIEW_PERMISSIONS);
    }

    /**
     * View permission policy.
     *
     * @param  User  $user
     * @param  Permission  $permission
     * @return bool
     */
    public function view(User $user, Permission $permission): bool
    {
        return $user->isAdmin() || $user->can(PermissionPermission::VIEW_PERMISSIONS);
    }

    /**
     * Create permission policy.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->can(PermissionPermission::CREATE_PERMISSIONS);
    }

    /**
     * Update permission policy.
     *
     * @param  User  $user
     * @param  Permission  $permission
     * @return bool
     */
    public function update(User $user, Permission $permission): bool
    {
        return $user->isAdmin() || $user->can(PermissionPermission::EDIT_PERMISSIONS);
    }

    /**
     * Delete permission policy.
     *
     * @param  User  $user
     * @param  Permission  $permission
     * @return bool
     */
    public function delete(User $user, Permission $permission): bool
    {
        return $user->isAdmin() || $user->can(PermissionPermission::DELETE_PERMISSIONS);
    }
}
