<?php

namespace Modules\Brand\Repositories\Criteria;

use App\Http\Filters\LiveFilter;
use App\Repositories\Criteria\BaseCriteria;
use Prettus\Repository\Contracts\RepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class BrandRequestCriteria extends BaseCriteria
{
    /**
     * @inheritdoc
     */
    protected array $allowedModelFields = [
        'id',
        'user_id',
        'name',
        'slug',
        'description',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * @inheritDoc
     */
    public function apply($model, RepositoryInterface $repository)
    {
        return QueryBuilder::for($model)
            ->defaultSort('-id')
            ->allowedFields($this->allowedModelFields())
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('user__id'),
                AllowedFilter::partial('name'),
                AllowedFilter::partial('slug'),
                AllowedFilter::custom('live', new LiveFilter([
                    'id' => '=',
                    'name' => 'like',
                    'slug' => 'like',
                ])),
                AllowedFilter::trashed(),
            ])
            ->allowedIncludes([
                'users',
            ])
            ->allowedSorts([
                'id', 'name', 'slug', 'created_at', 'updated_at', 'deleted_at',
            ]);
    }
}
