<?php

declare(strict_types=1);

namespace Modules\Role\Repositories\Criteria;

use App\Repositories\Criteria\BaseCriteria;
use Prettus\Repository\Contracts\RepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class PermissionRequestCriteria extends BaseCriteria
{
    /**
     * {@inheritdoc}
     */
    protected static array $allowedModelFields = [
        'id',
        'name',
        'description',
        'guard_name',
        'module',
    ];

    /**
     * {@inheritDoc}
     */
    public function apply($model, RepositoryInterface $repository)
    {
        return QueryBuilder::for($model)
            ->defaultSort('id')
            ->allowedFields(array_merge(
                self::$allowedModelFields,
                ColumnRequestCriteria::allowedModelFields('columns'),
            ))
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::partial('name'),
                AllowedFilter::partial('description'),
            ])
            ->allowedSorts(['name', 'id', 'description'])
            ->allowedIncludes(['roles', 'columns']);
    }
}
