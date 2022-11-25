<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;
use Modules\Role\Enums\DefaultRole;
use Modules\User\Enums\UserPermission;
use Modules\User\Models\User;

abstract class BasePolicy
{
    use HandlesAuthorization;

    /**
     * Is admin.
     *
     * @param User $user
     * @return bool
     */
    protected function isAdmin(User $user): bool
    {
        return $user->hasRole(DefaultRole::ADMIN);
    }

    public function viewAny(User $user): bool
    {
        $permissions = $user->permissions()
            ->where('name', UserPermission::VIEW_USERS)
            ->with('column')
            ->get()
            ->pluck('column.name');
dd($permissions);
//        $fields = request()->get('field[users]');
        return true;
    }

    public function view(User $user, Model $model): bool
    {
        return true;
    }
}
