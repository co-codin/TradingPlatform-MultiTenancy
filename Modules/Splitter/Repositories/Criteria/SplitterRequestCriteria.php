<?php

declare(strict_types=1);

namespace Modules\Splitter\Repositories\Criteria;

use App\Repositories\Criteria\BaseCriteria;
use Prettus\Repository\Contracts\RepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class SplitterRequestCriteria extends BaseCriteria
{
    protected static array $allowedModelFields = [
        'id',
        'user_id',
        'name',
        'is_active',
        'conditions',
        'share_conditions',
        'position',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    /**
     * {@inheritDoc}
     */
    public function apply($model, RepositoryInterface $repository)
    {
        return QueryBuilder::for($model)
            ->defaultSort('-is_active')
            ->defaultSort('position')
            ->allowedFields(array_merge(
                self::allowedModelFields(),
            ))
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('user_id'),
                AllowedFilter::exact('is_active'),
                AllowedFilter::exact('position'),
                AllowedFilter::partial('name'),
                AllowedFilter::partial('created_at'),
                AllowedFilter::trashed(),
            ])
            ->allowedIncludes([])
            ->allowedSorts([
                'id',
                'user_id',
                'name',
                'is_active',
                'conditions',
                'share_conditions',
                'position',
                'deleted_at',
                'created_at',
                'updated_at',
            ]);
    }
}
