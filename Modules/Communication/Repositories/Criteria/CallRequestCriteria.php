<?php

declare(strict_types=1);

namespace Modules\Communication\Repositories\Criteria;

use App\Repositories\Criteria\BaseCriteria;
use Modules\User\Repositories\Criteria\UserRequestCriteria;
use Prettus\Repository\Contracts\RepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class CallRequestCriteria extends BaseCriteria
{
    protected static array $allowedModelFields = [
        'id',
        'user_id',
        'callable_type',
        'callable_id',
        'provider_id',
        'duration',
        'text',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function apply($model, RepositoryInterface $repository)
    {
        return QueryBuilder::for($model)
            ->defaultSort('-id')
            ->allowedFields(array_merge(
                self::$allowedModelFields,
                UserRequestCriteria::allowedModelFields('users'),
            ))
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('user_id'),
                AllowedFilter::exact('callable_id'),
                AllowedFilter::exact('provider_id'),
                AllowedFilter::partial('duration'),
                AllowedFilter::partial('text'),
                AllowedFilter::partial('status'),
                AllowedFilter::trashed(),
            ])
            ->allowedIncludes([
                'user',
                'provider',
                'sendcallable',
                'callable',
            ])
            ->with([
                'user',
                'provider',
            ])
            ->allowedSorts([
                'id',
                'user_id',
                'provider_id',
                'duration',
                'text',
                'status',
                'created_at',
                'updated_at',
                'deleted_at',
            ]);
    }
}
