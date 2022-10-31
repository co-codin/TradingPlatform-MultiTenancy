<?php

namespace Modules\Worker\Policies;

use App\Policies\BasePolicy;
use Modules\Worker\Enums\WorkerPermission;
use Modules\Worker\Models\Worker;

class WorkerPolicy extends BasePolicy
{
    public function viewAny(Worker $user): bool
    {
        return $user->can(WorkerPermission::VIEW_WORKERS);
    }

    public function view(Worker $user, Worker $selectedWorker): bool
    {
        return $user->can(WorkerPermission::VIEW_WORKERS);
    }

    public function create(Worker $user): bool
    {
        return $user->can(WorkerPermission::CREATE_WORKERS);
    }

    public function update(Worker $user, Worker $selectedWorker): bool
    {
        return $user->can(WorkerPermission::EDIT_WORKERS);
    }

    public function delete(Worker $user, Worker $selectedWorker): bool
    {
        return $user->can(WorkerPermission::DELETE_WORKERS);
    }
}
