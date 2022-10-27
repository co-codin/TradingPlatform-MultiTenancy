<?php

namespace Modules\Role\Policies;

use App\Policies\BasePolicy;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Role\Models\Role;
use Modules\User\Models\User;

class RolePolicy extends BasePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function view(User $user, Role $role): bool
    {
        return $this->isAdmin($user);
    }

    public function create(User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function update(User $user, Role $role): bool
    {
        return $this->isAdmin($user);
    }

    public function delete(User $user, Role $role): bool
    {
        return $this->isAdmin($user);
    }
}
