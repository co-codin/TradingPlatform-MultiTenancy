<?php

declare(strict_types=1);

namespace Modules\Campaign\Repositories\Criteria;

use App\Repositories\Criteria\BaseCriteria;
use Prettus\Repository\Contracts\RepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class CampaignTransactionRequestCriteria extends BaseCriteria
{
    protected static array $allowedModelFields = [
        'id',
        'affiliate_id',
        'type',
        'amount',
        'customer_ids',
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
                AllowedFilter::exact('affiliate_id'),
                AllowedFilter::partial('type'),
                AllowedFilter::partial('amount'),
                AllowedFilter::partial('created_at'),
                AllowedFilter::trashed(),
            ])
            ->allowedIncludes([])
            ->allowedSorts([
                'id',
                'affiliate_id',
                'type',
                'amount',
                'customer_ids',
                'created_at',
                'updated_at',
            ]);
    }
}
