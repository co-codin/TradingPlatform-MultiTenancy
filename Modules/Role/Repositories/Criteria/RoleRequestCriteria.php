<?php

namespace Modules\Role\Repositories\Criteria;

use App\Repositories\Criteria\BaseCriteria;
use Prettus\Repository\Contracts\RepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class RoleRequestCriteria extends BaseCriteria
{
    /**
     * @inheritdoc
     */
    protected array $allowedModelFields = [
        'id',
        'name',
        'key',
        'guard_name',
    ];

    public function apply($model, RepositoryInterface $repository)
    {
        return QueryBuilder::for($model)
            ->defaultSort('id')
            ->allowedFields($this->allowedModelFields())
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::partial('name'),
            ])
            ->allowedSorts(['name', 'id'])
            ->allowedIncludes([
                'permissions',
                'usersCount',
                'permissionsCount',
            ]);
    }
}
