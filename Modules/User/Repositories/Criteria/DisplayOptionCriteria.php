<?php

declare(strict_types=1);

namespace Modules\User\Repositories\Criteria;

use App\Repositories\Criteria\BaseCriteria;
use Prettus\Repository\Contracts\RepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class DisplayOptionCriteria extends BaseCriteria
{
    /**
     * {@inheritDoc}
     */
    public function apply($model, RepositoryInterface $repository)
    {
        return QueryBuilder::for($model)
            ->defaultSort('-id')
            ->allowedFields([
                'id',
                'name',
                'user_id',
                'model_id',
                'columns',
                'per_page',
                'created_at',
                'updated_at',
            ])
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('user_id'),
                AllowedFilter::exact('model_id'),
                AllowedFilter::partial('name'),
                AllowedFilter::exact('model.name'),
                AllowedFilter::trashed(),
            ])
            ->allowedIncludes([
                'user',
                'model',
            ])
            ->allowedSorts([
                'id',
                'name',
                'user_id',
                'model_id',
                'created_at',
                'updated_at',
            ]);
    }
}
