<?php

namespace Modules\Communication\Repositories\Criteria;

use App\Repositories\Criteria\BaseCriteria;
use Prettus\Repository\Contracts\RepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class EmailTemplatesRequestCriteria extends BaseCriteria
{
    protected static array $allowedModelFields = [
        'id',
        'name',
        'body',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function apply($model, RepositoryInterface $repository)
    {
        return QueryBuilder::for($model)
            ->defaultSort('-id')
            ->allowedFields(self::$allowedModelFields)
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('name'),
                AllowedFilter::partial('body'),
                AllowedFilter::trashed(),
            ])
            ->allowedSorts([
                'id', 'name', 'body', 'created_at', 'updated_at', 'deleted_at',
            ]);
    }
}
