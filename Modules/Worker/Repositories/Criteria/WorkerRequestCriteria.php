<?php

namespace Modules\Worker\Repositories\Criteria;

use App\Http\Filters\LiveFilter;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class WorkerRequestCriteria implements CriteriaInterface
{
    public function apply($model, RepositoryInterface $repository)
    {
        return QueryBuilder::for($model)
            ->defaultSort('-id')
            ->allowedFields(['id', 'username', 'first_name', 'last_name', 'email', 'is_active', 'created_at', 'updated_at', 'deleted_at', 'last_login'])
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::partial('username'),
                AllowedFilter::partial('last_name'),
                AllowedFilter::exact('is_active'),
                AllowedFilter::custom('live', new LiveFilter([
                    'id' => '=',
                    'username' => 'like',
                    'last_name' => 'like',
                ])),
                AllowedFilter::trashed(),
            ])
            ->allowedIncludes([
                'desks'
            ])
            ->allowedSorts([
                'id', 'username', 'last_name', 'last_login', 'created_at', 'updated_at', 'deleted_at',
            ]);
    }
}
