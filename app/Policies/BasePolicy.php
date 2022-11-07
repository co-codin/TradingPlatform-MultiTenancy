<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Role\Enums\DefaultRole;
use Modules\User\Models\User;

abstract class BasePolicy
{
    use HandlesAuthorization;

    protected function isAdmin(User $user): bool
    {
        return $user->hasRole(DefaultRole::ADMIN);
    }
}
