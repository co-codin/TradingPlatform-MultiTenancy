<?php

namespace Modules\Role\Policies;

use App\Policies\BasePolicy;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Role\Models\Permission;
use Modules\User\Models\User;

class PermissionPolicy extends BasePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function view(User $user, Permission $permission): bool
    {
        return $this->isAdmin($user);
    }

    public function create(User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function update(User $user, Permission $permission): bool
    {
        return $this->isAdmin($user);
    }

    public function delete(User $user, Permission $permission): bool
    {
        return $this->isAdmin($user);
    }
}
