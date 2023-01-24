<?php

namespace Modules\Role\Policies;

use App\Policies\BasePolicy;
use Modules\Role\Enums\ActionPermission;
use Modules\User\Models\User;

class ActionPolicy  extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->can(ActionPermission::VIEW_ACTIONS);
    }
}
