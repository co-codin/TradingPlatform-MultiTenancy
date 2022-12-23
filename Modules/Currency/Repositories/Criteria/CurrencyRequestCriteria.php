<?php

declare(strict_types=1);

namespace Modules\Currency\Repositories\Criteria;

use App\Repositories\Criteria\BaseCriteria;
use Prettus\Repository\Contracts\RepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class CurrencyRequestCriteria extends BaseCriteria
{
    /**
     * {@inheritdoc}
     */
    protected static array $allowedModelFields = [
        'id',
        'name',
        'symbol',
        'iso3',
    ];

    /**
     * {@inheritDoc}
     */
    public function apply($model, RepositoryInterface $repository)
    {
        return QueryBuilder::for($model)
            ->defaultSort('-id')
            ->allowedFields(self::allowedModelFields())
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::partial('name'),
                AllowedFilter::partial('iso3'),
                AllowedFilter::partial('symbol'),
                AllowedFilter::exact('is_available'),
                AllowedFilter::partial('created_at'),
                AllowedFilter::partial('updated_at'),
                AllowedFilter::trashed(),
            ])
            ->allowedSorts([
                'id',
                'name',
                'symbol',
                'iso3',
                'is_available',
                'created_at',
                'updated_at',
            ]);
    }
}
