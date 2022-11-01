<?php

namespace Modules\Role\Policies;

use App\Policies\BasePolicy;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Role\Models\Permission;
use Modules\Worker\Models\Worker;

class PermissionPolicy extends BasePolicy
{
    use HandlesAuthorization;

    public function viewAny(Worker $worker): bool
    {
        return $this->isAdmin($worker);
    }

    public function view(Worker $worker, Permission $permission): bool
    {
        return $this->isAdmin($worker);
    }

    public function create(Worker $worker): bool
    {
        return $this->isAdmin($worker);
    }

    public function update(Worker $worker, Permission $permission): bool
    {
        return $this->isAdmin($worker);
    }

    public function delete(Worker $worker, Permission $permission): bool
    {
        return $this->isAdmin($worker);
    }
}
