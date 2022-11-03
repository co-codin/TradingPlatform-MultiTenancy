<?php

namespace Modules\Role\Repositories;

use App\Repositories\BaseRepository;
use Modules\Role\Models\Permission;
use Modules\Role\Repositories\Criteria\PermissionRequestCriteria;

class PermissionRepository extends BaseRepository
{
    public function model()
    {
        return Permission::class;
    }

    public function boot()
    {
        $this->pushCriteria(PermissionRequestCriteria::class);
    }
}
