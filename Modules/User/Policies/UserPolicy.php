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
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can(UserPermission::VIEW_USERS);
    }

    /**
     * View policy.
     *
     * @param User $user
     * @param User $model
     * @return bool
     */
    public function view(User $user, User $model): bool
    {
        return $user->can(UserPermission::VIEW_USERS);
    }

    /**
     * Create policy.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can(UserPermission::CREATE_USERS);
    }

    /**
     * Update policy.
     *
     * @param User $user
     * @param User $selectedUser
     * @return bool
     */
    public function update(User $user, User $selectedUser): bool
    {
        return match (true) {
            $user->id === $selectedUser?->id => true,
            $user->can(UserPermission::EDIT_USERS) => true,
            default => false,
        };
    }

    /**
     * Delete policy.
     *
     * @param User $user
     * @param User $selectedUser
     * @return bool
     */
    public function delete(User $user, User $selectedUser): bool
    {
        return $user->can(UserPermission::DELETE_USERS);
    }

    /**
     * Ban user policy.
     *
     * @param User $user
     * @return bool
     */
    public function ban(User $user): bool
    {
        return $user->can(UserPermission::BAN_USERS);
    }

    /**
     * Unban user policy.
     *
     * @param User $user
     * @return bool
     */
    public function unban(User $user): bool
    {
        return $user->can(UserPermission::BAN_USERS);
    }
}
