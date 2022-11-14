<?php

namespace Modules\Role\Repositories;

use App\Repositories\BaseRepository;
use Modules\Role\Models\Permission;
use Modules\Role\Repositories\Criteria\PermissionRequestCriteria;

class PermissionRepository extends BaseRepository
{
    /**
     * @inheritDoc
     */
    public function model(): string
    {
        return Permission::class;
    }

    /**
     * @inheritDoc
     */
    public function boot()
    {
        $this->pushCriteria(PermissionRequestCriteria::class);
    }
}
