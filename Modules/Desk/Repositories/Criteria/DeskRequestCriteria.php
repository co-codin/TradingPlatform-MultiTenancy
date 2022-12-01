<?php

declare(strict_types=1);

namespace Modules\Desk\Repositories\Criteria;

use App\Http\Filters\LiveFilter;
use App\Repositories\Criteria\BaseCriteria;
use Modules\Customer\Repositories\Criteria\CustomerRequestCriteria;
use Prettus\Repository\Contracts\RepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class DeskRequestCriteria extends BaseCriteria
{
    protected static array $allowedModelFields = [
        'id',
        'name',
        'title',
        'is_active',
        'created_at',
        'updated_at',
        'deleted_at',
        'parent_id',
    ];

    /**
     * {@inheritDoc}
     */
    public function apply($model, RepositoryInterface $repository)
    {
        return QueryBuilder::for($model)
            ->defaultSort('-id')
            ->allowedFields(array_merge(
                self::$allowedModelFields,
                CustomerRequestCriteria::allowedModelFields('customers')
            ))
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::partial('name'),
                AllowedFilter::partial('title'),
                AllowedFilter::exact('is_active'),
                AllowedFilter::exact('parent_id'),
                AllowedFilter::custom('live', new LiveFilter([
                    'id' => '=',
                    'name' => 'like',
                    'title' => 'like',
                ])),
                AllowedFilter::trashed(),
            ])
            ->allowedIncludes([
                'parent',
                'ancestors',
                'descendants',
                'children',
                'customers',
            ])
            ->allowedSorts([
                'id', 'name', 'title', 'created_at', 'updated_at', 'deleted_at',
            ]);
    }
}
