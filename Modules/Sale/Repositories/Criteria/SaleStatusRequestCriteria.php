<?php

declare(strict_types=1);

namespace Modules\Sale\Repositories\Criteria;

use App\Repositories\Criteria\BaseCriteria;
use Prettus\Repository\Contracts\RepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class SaleStatusRequestCriteria extends BaseCriteria
{
    /**
     * @inheritDoc
     */
    public function apply($model, RepositoryInterface $repository)
    {
        return QueryBuilder::for($model)
        ->defaultSort('-id')
        ->allowedFields(
            static::allowedSaleStatusFields()
        )
        ->allowedFilters([
            AllowedFilter::exact('id'),
            AllowedFilter::partial('name'),
            AllowedFilter::partial('title'),
            AllowedFilter::partial('created_at'),
            AllowedFilter::partial('updated_at'),
            AllowedFilter::trashed(),
        ])
        ->allowedSorts([
            'id',
            'name',
            'title',
            'created_at',
            'updated_at',
            'deleted_at',
        ]);
    }

    public static function allowedSaleStatusFields($prefix = null): array
    {
        $fields = [
            'id',
            'name',
            'title',
        ];

        if(!$prefix) {
            return $fields;
        }

        return array_map(fn($field) => $prefix . "." . $field, $fields);
    }

}
