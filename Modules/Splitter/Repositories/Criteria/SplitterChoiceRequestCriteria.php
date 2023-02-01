<?php

declare(strict_types=1);

namespace Modules\Splitter\Repositories\Criteria;

use App\Repositories\Criteria\BaseCriteria;
use Prettus\Repository\Contracts\RepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class SplitterChoiceRequestCriteria extends BaseCriteria
{
    protected static array $allowedModelFields = [
        'id',
        'splitter_id',
        'type',
        'option_per_day',
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
            ->defaultSort('-id')
            ->allowedFields(
                self::allowedModelFields()
            )
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('splitter_id'),
                AllowedFilter::exact('type'),
                AllowedFilter::exact('option_per_day'),
                AllowedFilter::partial('created_at'),
                AllowedFilter::trashed(),
            ])
            ->scopes([
                'currentBrand',
            ])
            ->allowedIncludes([
                'splitter',
                'workers',
                'desks',
            ])
            ->allowedSorts([
                'id',
                'splitter_id',
                'type',
                'option_per_day',
                'created_at',
                'updated_at',
            ]);
    }
}
