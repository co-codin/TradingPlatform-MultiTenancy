<?php

namespace Modules\Role\Policies;

use App\Policies\BasePolicy;
use Illuminate\Database\Eloquent\Model;
use Modules\Role\Enums\RolePermission;
use Modules\Role\Models\Role;
use Modules\User\Models\User;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class RolePolicy extends BasePolicy
{
    /**
     * View any policy.
     *
     * @param User $user
     * @return bool
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * View policy.
     *
     * @param User $user
     * @param Model $model
     * @return bool
     */
    public function view(User $user, Model $model): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Role $role): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Role $role): bool
    {
        return $user->isAdmin();
    }
}
