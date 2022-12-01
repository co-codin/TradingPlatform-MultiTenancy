<?php

declare(strict_types=1);

namespace Modules\Config\Repositories\Criteria;

use App\Http\Filters\LiveFilter;
use App\Repositories\Criteria\BaseCriteria;
use Prettus\Repository\Contracts\RepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class ConfigTypeRequestCriteria extends BaseCriteria
{
    /**
     * {@inheritdoc}
     */
    protected static array $allowedModelFields = [
        'name',
    ];

    /**
     * {@inheritDoc}
     */
    public function apply($model, RepositoryInterface $repository)
    {
        return QueryBuilder::for($model)
            ->defaultSort('-id')
            ->allowedFields(
                self::allowedModelFields(),
                ConfigRequestCriteria::allowedModelFields('configs')
            )
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::custom('live', new LiveFilter([
                    'id' => '=',
                ])),
                AllowedFilter::trashed(),
            ])
            ->allowedIncludes([
                'configs',
            ])
            ->allowedSorts([
                'id',
            ]);
    }
}
