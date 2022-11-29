<?php

namespace Modules\Role\Repositories;

use App\Repositories\BaseRepository;
use Modules\Role\Models\Role;
use Modules\Role\Repositories\Criteria\RoleRequestCriteria;

class RoleRepository extends BaseRepository
{
    /**
     * @inheritDoc
     */
    public function model(): string
    {
        return Role::class;
    }

    /**
     * @inheritDoc
     */
    public function boot()
    {
        $this->pushColumnPermissionValidator(RoleColumnPermissionValidator::class);
        $this->pushCriteria(RoleRequestCriteria::class);
    }
}
