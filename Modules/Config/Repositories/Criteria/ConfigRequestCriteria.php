<?php

declare(strict_types=1);

namespace Modules\Config\Repositories\Criteria;

use App\Http\Filters\LiveFilter;
use App\Repositories\Criteria\BaseCriteria;
use Prettus\Repository\Contracts\RepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class ConfigRequestCriteria extends BaseCriteria
{
    /**
     * {@inheritdoc}
     */
    protected static array $allowedModelFields = [
        'data_type',
        'name',
        'value',
        'description',
    ];

    /**
     * {@inheritDoc}
     */
    public function apply($model, RepositoryInterface $repository)
    {
        return QueryBuilder::for($model)
            ->defaultSort('-id')
            ->allowedFields(
                static::$allowedModelFields,
                ConfigTypeRequestCriteria::allowedModelFields('config_type'),
            )
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::custom('live', new LiveFilter([
                    'id' => '=',
                ])),
                AllowedFilter::partial('description'),
                AllowedFilter::trashed(),
            ])
            ->allowedIncludes([
                'config_type',
            ])
            ->allowedSorts([
                'id',
            ]);
    }
}
