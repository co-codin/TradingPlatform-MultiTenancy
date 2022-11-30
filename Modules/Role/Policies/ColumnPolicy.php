<?php

declare(strict_types=1);

namespace Modules\Role\Policies;

use App\Policies\BasePolicy;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Role\Enums\ColumnPermission;
use Modules\Role\Models\Permission;
use Modules\User\Models\User;

final class ColumnPolicy extends BasePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->can(ColumnPermission::VIEW_COLUMNS);
    }

    public function view(User $user, Permission $permission): bool
    {
        return $user->isAdmin() || $user->can(ColumnPermission::VIEW_COLUMNS);
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->can(ColumnPermission::CREATE_COLUMNS);
    }

    public function update(User $user, Permission $permission): bool
    {
        return $user->isAdmin() || $user->can(ColumnPermission::EDIT_COLUMNS);
    }

    public function delete(User $user, Permission $permission): bool
    {
        return $user->isAdmin() || $user->can(ColumnPermission::DELETE_COLUMNS);
    }
}
