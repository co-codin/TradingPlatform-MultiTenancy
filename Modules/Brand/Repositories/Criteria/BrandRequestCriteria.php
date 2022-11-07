<?php

namespace Modules\Brand\Repositories\Criteria;

use App\Http\Filters\LiveFilter;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class BrandRequestCriteria implements CriteriaInterface
{
    public function apply($model, RepositoryInterface $repository)
    {
        return QueryBuilder::for($model)
            ->defaultSort('-id')
            ->allowedFields(['id', 'worker_id', 'name', 'slug', 'description', 'created_at', 'updated_at', 'deleted_at'])
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('worker_id'),
                AllowedFilter::partial('name'),
                AllowedFilter::partial('slug'),
                AllowedFilter::custom('live', new LiveFilter([
                    'id' => '=',
                    'name' => 'like',
                    'slug' => 'like',
                ])),
                AllowedFilter::trashed(),
            ])
            ->allowedIncludes([
                'users',
            ])
            ->allowedSorts([
                'id', 'name', 'slug', 'created_at', 'updated_at', 'deleted_at',
            ]);
    }
}
