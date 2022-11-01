<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Role\Enums\DefaultRole;
use Modules\Worker\Models\Worker;

abstract class BasePolicy
{
    use HandlesAuthorization;

    protected function isAdmin(Worker $user): bool
    {
        return $user->hasRole(DefaultRole::ADMIN);
    }
}
