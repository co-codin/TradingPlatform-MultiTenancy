<?php

declare(strict_types=1);

namespace Modules\Transaction\Repositories\Criteria;

use App\Http\Filters\LiveFilter;
use App\Repositories\Criteria\BaseCriteria;
use Prettus\Repository\Contracts\RepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class TransactionsMethodRequestCriteria extends BaseCriteria
{
    protected static array $allowedModelFields = [
        'id',
        'name',
        'title',
        'is_active',
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
                AllowedFilter::exact('is_active'),
                AllowedFilter::trashed(),
            ])
            ->allowedIncludes([])
            ->allowedSorts([
                'id',
                'name',
                'title',
                'is_active',
                'created_at',
                'updated_at',
                'deleted_at',
            ]);
    }
}
