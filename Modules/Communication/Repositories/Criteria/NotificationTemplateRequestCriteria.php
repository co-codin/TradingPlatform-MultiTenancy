<?php

declare(strict_types=1);

namespace Modules\Communication\Repositories\Criteria;

use App\Repositories\Criteria\BaseCriteria;
use Modules\User\Repositories\Criteria\UserRequestCriteria;
use Prettus\Repository\Contracts\RepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class NotificationTemplateRequestCriteria extends BaseCriteria
{
    /**
     * {@inheritdoc}
     */
    protected static array $allowedModelFields = [
        'id',
        'data',
        'creator_id',
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
                UserRequestCriteria::allowedModelFields('creator')
            )
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('creator_id'),
                AllowedFilter::partial('data'),
            ])
            ->allowedSorts([
                'id',
                'creator_id',
                'created_at',
                'updated_at',
            ])
            ->allowedIncludes([
                'creator',
            ]);
    }
}
