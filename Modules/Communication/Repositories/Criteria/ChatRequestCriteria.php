<?php

declare(strict_types=1);

namespace Modules\Communication\Repositories\Criteria;

use App\Repositories\Criteria\BaseCriteria;
use Prettus\Repository\Contracts\RepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class ChatRequestCriteria extends BaseCriteria
{
    protected static array $allowedModelFields = [
        'id',
        'user_id',
        'customer_id',
        'message',
        'initiator_id',
        'initiator_type',
        'read',
        'created_at',
        'updated_at',
    ];

    public function apply($model, RepositoryInterface $repository)
    {
        return QueryBuilder::for($model)
            ->defaultSort('id')
            ->allowedFields(self::$allowedModelFields)
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('user_id'),
                AllowedFilter::exact('customer_id'),
                AllowedFilter::partial('message'),
                AllowedFilter::exact('read'),
                AllowedFilter::trashed(),
            ])
            ->allowedIncludes([
                'user', 'customer'
            ])
            ->with([
                'user', 'customer'
            ])
            ->allowedSorts([
                'id', 'user_id', 'customer_id', 'message', 'created_at', 'updated_at',
            ]);
    }
}
