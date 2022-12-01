<?php

declare(strict_types=1);

namespace Modules\Department\Repositories\Criteria;

use App\Repositories\Criteria\BaseCriteria;
use Prettus\Repository\Contracts\RepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class DepartmentRequestCriteria extends BaseCriteria
{
    protected static array $allowedModelFields = [
        'id',
        'name',
        'title',
        'is_active',
        'is_default',
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
                AllowedFilter::partial('title'),
                AllowedFilter::partial('is_active'),
                AllowedFilter::partial('is_default'),
                AllowedFilter::trashed(),
            ])
            ->allowedSorts([
                'id',
                'name',
                'title',
                'is_active',
                'is_default',
                'created_at',
                'updated_at',
                'deleted_at',
            ]);
    }
}
