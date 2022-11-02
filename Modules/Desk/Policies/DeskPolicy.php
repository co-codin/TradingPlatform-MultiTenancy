<?php

namespace Modules\Desk\Policies;

use App\Policies\BasePolicy;
use Modules\Desk\Enums\DeskPermission;
use Modules\Desk\Models\Desk;
use Modules\Worker\Models\Worker;

class DeskPolicy extends BasePolicy
{
    public function viewAny(Worker $worker): bool
    {
        return $worker->can(DeskPermission::VIEW_DESKS);
    }

    public function view(Worker $worker, Desk $desk): bool
    {
        return $worker->can(DeskPermission::VIEW_DESKS);
    }

    public function create(Worker $worker): bool
    {
        return $worker->can(DeskPermission::CREATE_DESKS);
    }

    public function update(Worker $worker, Desk $desk): bool
    {
        return $worker->can(DeskPermission::EDIT_DESKS);
    }

    public function delete(Worker $worker, Desk $desk): bool
    {
        return $worker->can(DeskPermission::DELETE_DESKS);
    }
}
