<?php

namespace Modules\Brand\Policies;

use App\Policies\BasePolicy;
use Modules\Brand\Enums\BrandPermission;
use Modules\Brand\Models\Brand;
use Modules\Worker\Models\Worker;

class BrandPolicy extends BasePolicy
{
    public function viewAny(Worker $worker): bool
    {
        return $worker->can(BrandPermission::VIEW_BRANDS);
    }

    public function view(Worker $worker, Brand $brand): bool
    {
        return $worker->can(BrandPermission::VIEW_BRANDS);
    }

    public function create(Worker $worker): bool
    {
        return $worker->can(BrandPermission::CREATE_BRANDS);
    }

    public function update(Worker $worker, Brand $brand): bool
    {
        return $worker->can(BrandPermission::EDIT_BRANDS);
    }

    public function delete(Worker $worker, Brand $brand): bool
    {
        return $worker->can(BrandPermission::DELETE_BRANDS);
    }
}
