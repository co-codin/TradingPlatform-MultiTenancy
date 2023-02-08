<?php

declare(strict_types=1);

namespace Modules\Geo\Repositories\Criteria;

use App\Repositories\Criteria\BaseCriteria;
use Prettus\Repository\Contracts\RepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class CountryRequestCriteria extends BaseCriteria
{
    /**
     * {@inheritdoc}
     */
    protected static array $allowedModelFields = [
        'id',
        'name',
        'iso2',
        'iso3',
    ];

    /**
     * {@inheritDoc}
     */
    public function apply($model, RepositoryInterface $repository)
    {
        return QueryBuilder::for($model)
            ->defaultSort('-id')
            ->allowedFields(self::$allowedModelFields)
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::partial('name'),
                AllowedFilter::partial('iso2'),
                AllowedFilter::partial('iso3'),
                AllowedFilter::partial('created_at'),
                AllowedFilter::partial('updated_at'),
                AllowedFilter::trashed(),
            ])
            ->scopes([
                'isForbidden',
            ])
            ->allowedSorts([
                'id',
                'name',
                'iso2',
                'iso3',
                'created_at',
                'updated_at',
                'deleted_at',
            ]);
    }
}
