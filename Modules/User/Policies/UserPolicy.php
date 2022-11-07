<?php

namespace Modules\User\Policies;

use App\Policies\BasePolicy;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\User\Enums\UserPermission;
use Modules\User\Models\User;

class UserPolicy extends BasePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can(UserPermission::VIEW_USERS);
    }

    public function view(User $user, User $selectedUser): bool
    {
        return $user->can(UserPermission::VIEW_USERS);
    }

    public function create(User $user): bool
    {
        return $user->can(UserPermission::CREATE_USERS);
    }

    public function update(User $user, User $selectedUser): bool
    {
        return $user->can(UserPermission::EDIT_USERS);
    }

    public function delete(User $user, User $selectedUser): bool
    {
        return $user->can(UserPermission::DELETE_USERS);
    }
}
