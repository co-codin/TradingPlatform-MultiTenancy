<?php

namespace Modules\Role\Repositories;

use App\Repositories\BaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Modules\Role\Models\Permission;
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

    public function jsonPaginate(int $maxResults = null, int $defaultSize = null)
    {
        $paginate = parent::jsonPaginate($maxResults, $defaultSize);

        return new LengthAwarePaginator($paginate->getCollection(), $paginate->total(), $paginate->perPage(), $paginate->currentPage(), [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => $paginate->getPageName(),
            'totalPermissions' => Permission::count(),
        ]);
    }
}
