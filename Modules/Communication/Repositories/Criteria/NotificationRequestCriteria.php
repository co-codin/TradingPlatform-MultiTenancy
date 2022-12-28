<?php

declare(strict_types=1);

namespace Modules\Communication\Repositories\Criteria;

use App\Repositories\Criteria\BaseCriteria;
use Modules\Customer\Repositories\Criteria\CustomerRequestCriteria;
use Modules\User\Repositories\Criteria\UserRequestCriteria;
use Prettus\Repository\Contracts\RepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class NotificationRequestCriteria extends BaseCriteria
{
    /**
     * {@inheritdoc}
     */
    protected static array $allowedModelFields = [
        'id',
        'data',
        'notifiable_id',
        'notifiable_type',
        'read_at',
        'created_at',
        'updated_at',
    ];

    /**
     * {@inheritDoc}
     */
    public function apply($model, RepositoryInterface $repository)
    {
        return QueryBuilder::for($model)
            ->defaultSort('-id')
            ->allowedFields(
                self::$allowedModelFields,
                array_merge(
                    UserRequestCriteria::allowedModelFields('sender'),
                    CustomerRequestCriteria::allowedModelFields('sender'),
                ),
            )
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('notifiable_id'),
                AllowedFilter::partial('data'),
            ])
            ->allowedSorts([
                'id',
                'created_at',
                'read_at',
                'updated_at',
            ])
            ->allowedIncludes([
                'sender',
            ]);
    }
}
