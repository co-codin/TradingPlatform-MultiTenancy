<?php

namespace Modules\Department\Repositories;

use App\Repositories\BaseRepository;
use Modules\Department\Models\Department;
use Modules\Department\Repositories\Criteria\DepartmentRequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;

class DepartmentRepository extends BaseRepository
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return Department::class;
    }

    /**
     * {@inheritDoc}
     *
     * @throws RepositoryException
     */
    public function boot()
    {
        $this->pushPermissionColumnValidator(DepartmentColumnPermissionValidator::class);
        $this->pushCriteria(DepartmentRequestCriteria::class);
    }
}
