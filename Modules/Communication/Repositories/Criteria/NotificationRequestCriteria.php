<?php

declare(strict_types=1);

namespace Modules\Communication\Repositories\Criteria;

use App\Repositories\Criteria\BaseCriteria;
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
        'type',
        'notifiable',
        'data',
        'user_id',
        'read_at',
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
                UserRequestCriteria::allowedModelFields('user')
            )
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('user_id'),
                AllowedFilter::exact('read_at'),
                AllowedFilter::exact('notifiable'),
                AllowedFilter::partial('type'),
                AllowedFilter::partial('data'),
            ])
            ->allowedSorts([
                'id',
                'user_id',
                'read_at',
                'type',
                'created_at',
                'updated_at',
            ])
            ->allowedIncludes([
                'user',
                'notifiable',
            ]);
    }
}
