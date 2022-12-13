<?php

declare(strict_types=1);

namespace Modules\Customer\Repositories\Criteria;

use App\Http\Filters\LiveFilter;
use App\Repositories\Criteria\BaseCriteria;
use Modules\Department\Repositories\Criteria\DepartmentRequestCriteria;
use Modules\Desk\Repositories\Criteria\DeskRequestCriteria;
use Modules\User\Repositories\Criteria\UserRequestCriteria;
use Prettus\Repository\Contracts\RepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class CustomerRequestCriteria extends BaseCriteria
{
    protected static array $allowedModelFields = [
        'id',
        'user_id',
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
                UserRequestCriteria::allowedModelFields('affiliateUser'),
                DeskRequestCriteria::allowedModelFields('desk'),
                DepartmentRequestCriteria::allowedModelFields('department')
            ))
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('user_id'),
                AllowedFilter::partial('first_name'),
                AllowedFilter::partial('last_name'),
                AllowedFilter::partial('email'),
                AllowedFilter::exact('desk_id'),
                AllowedFilter::exact('department_id'),
                AllowedFilter::exact('country_id'),
                AllowedFilter::exact('created_at'),
                AllowedFilter::exact('last_online'),

                AllowedFilter::trashed(),
            ])
            ->allowedIncludes([
                'desk', 'department', 'affiliateUser',
            ])
            ->allowedSorts([

            ]);
    }
}
