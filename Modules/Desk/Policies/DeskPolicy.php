<?php

namespace Modules\Desk\Policies;

use App\Policies\BasePolicy;
use Modules\Desk\Enums\DeskPermission;
use Modules\Desk\Models\Desk;
use Modules\User\Models\User;

class DeskPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can(DeskPermission::VIEW_DESKS);
    }

    public function view(User $user, Desk $desk): bool
    {
        return $user->can(DeskPermission::VIEW_DESKS);
    }

    public function create(User $user): bool
    {
        return $user->can(DeskPermission::CREATE_DESKS);
    }

    public function update(User $user, Desk $desk): bool
    {
        return $user->can(DeskPermission::EDIT_DESKS);
    }

    public function delete(User $user, Desk $desk): bool
    {
        return $user->can(DeskPermission::DELETE_DESKS);
    }
}
