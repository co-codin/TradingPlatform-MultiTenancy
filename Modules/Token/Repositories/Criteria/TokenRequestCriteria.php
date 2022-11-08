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
            ->allowedFields(['id', 'token', 'user_id', 'description', 'ip', 'created_at', 'updated_at', 'deleted_at'])
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::partial('token'),
                AllowedFilter::exact('user_id'),
                AllowedFilter::exact('ip'),
                AllowedFilter::trashed(),
            ])
            ->allowedIncludes([
                'user',
            ])
            ->allowedSorts([
                'id', 'user_id', 'ip', 'created_at', 'updated_at', 'deleted_at',
            ]);
    }
}
