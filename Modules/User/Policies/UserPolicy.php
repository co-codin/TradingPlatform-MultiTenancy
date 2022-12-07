<?php

namespace Modules\User\Policies;

use App\Policies\BasePolicy;
use Modules\User\Enums\UserPermission;
use Modules\User\Models\User;

class UserPolicy extends BasePolicy
{
    /**
     * View any policy.
     *
     * @param  User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return match (true) {
            $user->isAdmin() => true,
            $user->can(UserPermission::VIEW_USERS) => true,
            default => false,
        };
    }

    /**
     * View policy.
     *
     * @param  User  $user
     * @param  User  $selectedUser
     * @return bool
     */
    public function view(User $user, User $selectedUser): bool
    {
        return match (true) {
            $user->isAdmin() => true,
            $user->can(UserPermission::VIEW_USERS)
            && $user->brands()->whereHas('users', fn ($q) => $q->where('id', $selectedUser->id))->exists() => true,
            $user->id === $selectedUser->id => true,
            default => false,
        };
    }

    /**
     * Create policy.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return match (true) {
            $user->isAdmin() => true,
            $user->can(UserPermission::CREATE_USERS) => true,
            default => false,
        };
    }

    /**
     * Update policy.
     *
     * @param  User  $user
     * @param  User  $selectedUser
     * @return bool
     */
    public function update(User $user, User $selectedUser): bool
    {
        return match (true) {
            $user->isAdmin() => true,
            $user->can(UserPermission::EDIT_USERS)
            && $user->brands()->whereHas('users', fn ($q) => $q->where('id', $selectedUser->id))->exists() => true,
            $user->id === $selectedUser->id => true,
            default => false,
        };
    }

    /**
     * Delete policy.
     *
     * @param  User  $user
     * @param  User  $selectedUser
     * @return bool
     */
    public function delete(User $user, User $selectedUser): bool
    {
        return match (true) {
            $user->isAdmin() => true,
            $user->can(UserPermission::DELETE_USERS)
            && $user->brands()->whereHas('users', fn ($q) => $q->where('id', $selectedUser->id))->exists() => true,
            $user->id === $selectedUser->id => true,
            default => false,
        };
    }

    /**
     * Ban user policy.
     *
     * @param  User  $user
     * @return bool
     */
    public function ban(User $user): bool
    {
        return $user->can(UserPermission::BAN_USERS);
    }

    /**
     * Unban user policy.
     *
     * @param  User  $user
     * @return bool
     */
    public function unban(User $user): bool
    {
        return $user->can(UserPermission::BAN_USERS);
    }

    /**
     * View any departments workers policy.
     *
     * @param  User  $user
     * @return bool
     */
    public function viewAnyByDepartments(User $user): bool
    {
        return $user->can(UserPermission::VIEW_DEPARTMENT_USERS);
    }
}
