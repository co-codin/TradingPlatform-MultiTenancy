<?php

declare(strict_types=1);

namespace Modules\User\Repositories\Criteria;

use App\Http\Filters\LiveFilter;
use App\Repositories\Criteria\BaseCriteria;
use Modules\Brand\Repositories\Criteria\BrandRequestCriteria;
use Prettus\Repository\Contracts\RepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class UserRequestCriteria extends BaseCriteria
{
    protected static array $allowedModelFields = [
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
        'affiliate_id',
        'show_on_scoreboards',
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
                self::$allowedModelFields,
                BrandRequestCriteria::allowedModelFields('brands')
            ))
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::partial('username'),
                AllowedFilter::partial('email'),
                AllowedFilter::partial('last_name'),
                AllowedFilter::partial('affiliate_id'),
                AllowedFilter::partial('show_on_scoreboards'),
                AllowedFilter::custom('live', new LiveFilter([
                    'id' => '=',
                    'username' => 'like',
                    'last_name' => 'like',
                    'email' => 'like',
                ])),
                AllowedFilter::trashed(),
            ])
            ->scopes([
                'byPermissionsAccess',
            ])
            ->allowedIncludes([
                'roles',
                'roles.permissions',
                'parent',
                'ancestors',
                'descendants',
                'children',
                'brands',
                'desks',
                'departments',
                'languages',
                'displayOptions',
                'affiliate',
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
}
