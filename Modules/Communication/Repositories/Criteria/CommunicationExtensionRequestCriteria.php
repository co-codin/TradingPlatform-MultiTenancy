<?php

declare(strict_types=1);

namespace Modules\Communication\Repositories\Criteria;

use App\Repositories\Criteria\BaseCriteria;
use Prettus\Repository\Contracts\RepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class CommunicationExtensionRequestCriteria extends BaseCriteria
{
    /**
     * {@inheritdoc}
     */
    protected static array $allowedModelFields = [
        'id',
        'name',
        'user_id',
        'provider_id',
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
            )
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::partial('name'),
                AllowedFilter::partial('user_id'),
                AllowedFilter::partial('provider_id'),
                AllowedFilter::partial('created_at'),
                AllowedFilter::partial('updated_at'),
                AllowedFilter::trashed(),
            ])
            ->allowedSorts([
                'id',
                'name',
                'created_at',
                'updated_at',
            ]);
    }
}
