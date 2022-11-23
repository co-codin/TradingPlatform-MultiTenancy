<?php

namespace Modules\Customer\Repositories\Criteria;

use App\Http\Filters\LiveFilter;
use App\Repositories\Criteria\BaseCriteria;
use Modules\Department\Repositories\Criteria\DepartmentRequestCriteria;
use Modules\Desk\Repositories\Criteria\DeskRequestCriteria;
use Modules\User\Repositories\Criteria\UserRequestCriteria;
use Prettus\Repository\Contracts\RepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CustomerRequestCriteria extends BaseCriteria
{
    /**
     * @inheritDoc
     */
    public function apply($model, RepositoryInterface $repository)
    {
        return QueryBuilder::for($model)
            ->defaultSort('-id')
            ->allowedFields(array_merge(
                static::allowedCustomerFields(),
                UserRequestCriteria::allowedUserFields('affiliateUser'),
                DeskRequestCriteria::allowedDeskFields('desk'),
                DepartmentRequestCriteria::allowedDepartmentFields('department')
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
                'desk', 'department', 'affiliateUser'
            ])
            ->allowedSorts([

            ]);
    }

    public static function allowedCustomerFields($prefix = null): array
    {
        $fields = [
            'id',
            'user_id',

        ];

        if(!$prefix) {
            return $fields;
        }

        return array_map(fn($field) => $prefix . "." . $field, $fields);
    }
}
