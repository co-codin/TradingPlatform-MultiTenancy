<?php

namespace Modules\Role\Repositories\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class RoleRequestCriteria implements CriteriaInterface
{
    public function apply($model, RepositoryInterface $repository)
    {
        return QueryBuilder::for($model)
            ->defaultSort('id')
            ->allowedFields([
                'id', 'name', 'key', 'guard_name'
            ])
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::partial('name'),
            ])
            ->allowedSorts(['name', 'id'])
            ->allowedIncludes(['permissions'])
            ;
    }
}
