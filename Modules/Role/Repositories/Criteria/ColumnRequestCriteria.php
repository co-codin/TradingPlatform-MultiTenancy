<?php

declare(strict_types=1);

namespace Modules\Role\Repositories\Criteria;

use App\Repositories\Criteria\BaseCriteria;
use Prettus\Repository\Contracts\RepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class ColumnRequestCriteria extends BaseCriteria
{
    /**
     * {@inheritdoc}
     */
    protected array $allowedModelFields = [
        'id',
        'name',
    ];

    public function apply($model, RepositoryInterface $repository): QueryBuilder
    {
        return QueryBuilder::for($model)
            ->defaultSort('id')
            ->allowedFields($this->allowedModelFields())
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::partial('name'),
            ])
            ->allowedSorts(['name', 'id']);
    }
}
