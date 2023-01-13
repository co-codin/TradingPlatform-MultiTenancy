<?php

declare(strict_types=1);

namespace Modules\Campaign\Repositories\Criteria;

use App\Repositories\Criteria\BaseCriteria;
use Prettus\Repository\Contracts\RepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class CampaignRequestCriteria extends BaseCriteria
{
    protected static array $allowedModelFields = [
        'id',
        'name',
        'cpa',
        'working_hours',
        'daily_cap',
        'crg',
        'is_active',
        'balance',
        'monthly_cr',
        'monthly_pv',
        'crg_cost',
        'ftd_cost',
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
            ->allowedFields(array_merge(
                self::allowedModelFields(),
            ))
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::partial('name'),
                AllowedFilter::partial('cpa'),
                AllowedFilter::partial('daily_cap'),
                AllowedFilter::partial('crg'),
                AllowedFilter::partial('is_active'),
                AllowedFilter::partial('balance'),
                AllowedFilter::partial('monthly_cr'),
                AllowedFilter::partial('monthly_pv'),
                AllowedFilter::partial('crg_cost'),
                AllowedFilter::partial('ftd_cost'),
                AllowedFilter::partial('created_at'),
                AllowedFilter::trashed(),
            ])
            ->allowedIncludes([])
            // ->with('countries')
            ->allowedSorts([
                'id',
                'name',
                'cpa',
                'daily_cap',
                'crg',
                'is_active',
                'balance',
                'monthly_cr',
                'monthly_pv',
                'crg_cost',
                'ftd_cost',
                'created_at',
                'updated_at',
            ]);
    }
}
