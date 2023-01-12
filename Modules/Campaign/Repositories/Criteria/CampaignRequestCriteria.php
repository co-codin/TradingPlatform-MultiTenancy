<?php

declare(strict_types=1);

namespace Modules\Campaign\Repositories\Criteria;

use App\Http\Filters\LiveFilter;
use App\Repositories\Criteria\BaseCriteria;
use Prettus\Repository\Contracts\RepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class CampaignRequestCriteria extends BaseCriteria
{
    protected static array $allowedModelFields = [
        'id',
        'cpa',
        'working_hours',
        'daily_cap',
        'crg',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * {@inheritDoc}
     */
    public function apply($model, RepositoryInterface $repository)
    {
        return QueryBuilder::for($model)
            ->defaultSort('-id')
            ->allowedFields(array_merge(
                self::allowedModelFields(),
            ))
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::partial('cpa'),
                AllowedFilter::partial('daily_cap'),
                AllowedFilter::partial('crg'),
                AllowedFilter::trashed(),
            ])
            ->allowedIncludes([])
            ->allowedSorts([
                'id',
                'cpa',
                'daily_cap',
                'crg',
                'created_at',
                'updated_at',
                'deleted_at',
            ]);
    }
}
