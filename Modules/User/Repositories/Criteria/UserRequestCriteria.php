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
        'communication_provider_id',
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
                BrandRequestCriteria::allowedModelFields('brands'),
            ))
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::partial('username'),
                AllowedFilter::partial('email'),
                AllowedFilter::partial('last_name'),
                AllowedFilter::exact('show_on_scoreboards'),
                AllowedFilter::exact('affiliate_id'),
                AllowedFilter::exact('communication_provider_id'),
                AllowedFilter::custom('live', new LiveFilter([
                    'id' => '=',
                    'username' => 'like',
                    'last_name' => 'like',
                    'email' => 'like',
                ])),
                AllowedFilter::exact('roles.id'),
                AllowedFilter::exact('desks.id'),
                AllowedFilter::exact('departments.id'),
                AllowedFilter::exact('countries.id'),
                AllowedFilter::exact('languages.id'),
                AllowedFilter::exact('displayOptions.model_id'),
                AllowedFilter::trashed(),
            ])
            ->scopes([
                'byPermissionsAccess',
            ])
            ->allowedIncludes([
                'roles',
                'roles.permissions',
                'permissions',
                'parent',
                'ancestors',
                'descendants',
                'children',
                'brands',
                'displayOptions',
                'desks',
                'departments',
                'languages',
                'countries',
                'affiliate',

                'communicationProvider',
                'communicationExtensions',

                'affiliateCustomers',
                'conversionCustomers',
                'retentionCustomers',
                'complianceCustomers',
                'supportCustomers',
                'conversionManageCustomers',
                'retentionManageCustomers',
                'firstConversionCustomers',
                'firstRetentionCustomers',
            ])
            ->allowedSorts([
                'id',
                'username',
                'first_name',
                'last_name',
                'email',
                'is_active',
                'target',
                'last_login',
                'show_on_scoreboards',
                'created_at',
                'updated_at',
            ]);
    }
}
