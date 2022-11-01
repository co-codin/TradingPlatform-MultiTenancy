<?php

namespace Modules\Role\Policies;

use App\Policies\BasePolicy;
use Modules\Role\Models\Role;
use Modules\Worker\Models\Worker;

class RolePolicy extends BasePolicy
{
    public function viewAny(Worker $worker): bool
    {
        return $this->isAdmin($worker);
    }

    public function view(Worker $worker, Role $role): bool
    {
        return $this->isAdmin($worker);
    }

    public function create(Worker $worker): bool
    {
        return $this->isAdmin($worker);
    }

    public function update(Worker $worker, Role $role): bool
    {
        return $this->isAdmin($worker);
    }

    public function delete(Worker $worker, Role $role): bool
    {
        return $this->isAdmin($worker);
    }
}
