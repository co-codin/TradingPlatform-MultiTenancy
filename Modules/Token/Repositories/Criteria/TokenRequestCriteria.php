<?php

namespace Modules\Token\Repositories\Criteria;

use App\Http\Filters\LiveFilter;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TokenRequestCriteria implements CriteriaInterface
{
    public function apply($model, RepositoryInterface $repository)
    {
        return QueryBuilder::for($model)
            ->defaultSort('-id')
            ->allowedFields(['id', 'token', 'worker_id', 'description', 'ip', 'created_at', 'updated_at', 'deleted_at'])
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::partial('token'),
                AllowedFilter::exact('worker_id'),
                AllowedFilter::exact('ip'),
                AllowedFilter::trashed(),
            ])
            ->allowedIncludes([
                'worker',
            ])
            ->allowedSorts([
                'id', 'worker_id', 'ip', 'created_at', 'updated_at', 'deleted_at',
            ]);
    }
}
