<?php

declare(strict_types=1);

namespace Modules\Transaction\Repositories\Criteria;

use App\Http\Filters\LiveFilter;
use App\Repositories\Criteria\BaseCriteria;
use Prettus\Repository\Contracts\RepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class TransactionStatusRequestCriteria extends BaseCriteria
{
    protected static array $allowedModelFields = [
        'id',
        'name',
        'title',
        'is_active',
        'is_valid',
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
                AllowedFilter::partial('name'),
                AllowedFilter::partial('title'),
                AllowedFilter::partial('is_active'),
                AllowedFilter::partial('is_valid'),
                AllowedFilter::custom('live', new LiveFilter([
                    'id' => '=',
                    'name' => 'like',
                    'title' => 'like',
                ])),
                AllowedFilter::trashed(),
            ])
            ->allowedIncludes([])
            ->allowedSorts([
                'id',
                'name',
                'title',
                'is_active',
                'is_valid',
                'created_at',
                'updated_at',
                'deleted_at',
            ]);
    }
}
