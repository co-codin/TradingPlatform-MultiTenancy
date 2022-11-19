<?php

namespace Modules\Brand\Repositories\Criteria;

use App\Http\Filters\LiveFilter;
use App\Repositories\Criteria\BaseCriteria;
use Modules\User\Repositories\Criteria\UserRequestCriteria;
use Prettus\Repository\Contracts\RepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class BrandRequestCriteria extends BaseCriteria
{

    /**
     * @inheritDoc
     */
    public function apply($model, RepositoryInterface $repository)
    {
        return QueryBuilder::for($model)
            ->defaultSort('-id')
            ->allowedFields(array_merge(
                static::allowedBrandFields(),
                UserRequestCriteria::allowedUserFields('users'),
            ))
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('user_id'),
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

    public static function allowedBrandFields($prefix = null): array
    {
        $fields = [
            'id',
            'user_id',
            'name',
            'slug',
            'description',
            'created_at',
            'updated_at',
            'deleted_at',
        ];

        if(!$prefix) {
            return $fields;
        }

        return array_map(fn($field) => $prefix . "." . $field, $fields);
    }
}
