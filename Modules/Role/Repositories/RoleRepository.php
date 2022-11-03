<?php

namespace Modules\Role\Repositories;

use App\Repositories\BaseRepository;
use Modules\Role\Models\Role;
use Modules\Role\Repositories\Criteria\RoleRequestCriteria;

class RoleRepository extends BaseRepository
{
    public function model()
    {
        return Role::class;
    }

    public function boot()
    {
        $this->pushCriteria(RoleRequestCriteria::class);
    }
}
