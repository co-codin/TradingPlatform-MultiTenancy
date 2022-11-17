<?php

namespace Modules\User\Repositories\Criteria;

use App\Http\Filters\LiveFilter;
use App\Repositories\Criteria\BaseCriteria;
use Modules\Brand\Repositories\Criteria\BrandRequestCriteria;
use Prettus\Repository\Contracts\RepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UserRequestCriteria extends BaseCriteria
{
    /**
     * @inheritDoc
     */
    public function apply($model, RepositoryInterface $repository)
    {
        return QueryBuilder::for($model)
            ->defaultSort('-id')
            ->allowedFields(array_merge(
               static::allowedUserFields(),
                BrandRequestCriteria::allowedBrandFields('brands')
            ))
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::partial('username'),
                AllowedFilter::partial('email'),
                AllowedFilter::partial('last_name'),
                AllowedFilter::custom('live', new LiveFilter([
                    'id' => '=',
                    'username' => 'like',
                    'last_name' => 'like',
                    'email' => 'like',
                ])),
                AllowedFilter::trashed(),
            ])
            ->allowedIncludes([
                'roles',
                'roles.permissions',
                'parent',
                'ancestors',
                'descendants',
                'children',
                'brands',
            ])
            ->allowedSorts([
                'id',
                'last_name',
                'email',
                'last_login',
                'created_at',
                'updated_at',
            ]);
    }

    public static function allowedUserFields($prefix = null): array
    {
        $fields = [
            'id',
            'username',
            'first_name',
            'last_name',
            'is_active',
            'target',
            'last_login',
            'created_at',
            'parent_id',
            'email',
            'updated_at',
            'deleted_at',
        ];

        if(!$prefix) {
            return $fields;
        }

        return array_map(fn($field) => $prefix . "." . $field, $fields);
    }
}
