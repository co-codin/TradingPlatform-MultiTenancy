<?php

declare(strict_types=1);

namespace Modules\Communication\Repositories\Criteria;

use App\Repositories\Criteria\BaseCriteria;
use Modules\Customer\Repositories\Criteria\CustomerRequestCriteria;
use Modules\User\Repositories\Criteria\UserRequestCriteria;
use Prettus\Repository\Contracts\RepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class CommentRequestCriteria extends BaseCriteria
{
    /**
     * {@inheritDoc}
     */
    public function apply($model, RepositoryInterface $repository)
    {
        return QueryBuilder::for($model)
            ->defaultSort('-id')
            ->allowedFields(array_merge(
                self::allowedModelFields(),
                UserRequestCriteria::allowedModelFields('users'),
                CustomerRequestCriteria::allowedModelFields('customers'),
            ))
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('user_id'),
                AllowedFilter::exact('customer_id'),
                AllowedFilter::partial('body'),
                AllowedFilter::trashed(),
            ])
            ->allowedIncludes([
                'user',
                'customer',
                'attachments',
            ])
            ->allowedSorts([
                'id',
                'body',
                'user_id',
                'customer_id',
                'position',
                'created_at',
                'updated_at',
                'deleted_at',
            ]);
    }
}
