<?php

declare(strict_types=1);

namespace Modules\Language\Repositories\Criteria;

use App\Repositories\Criteria\BaseCriteria;
use Prettus\Repository\Contracts\RepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class LanguageRequestCriteria extends BaseCriteria
{
    /**
     * @inheritdoc
     */
    protected array $allowedModelFields = [
        'id',
        'name',
        'code',
    ];

    /**
     * @inheritDoc
     */
    public function apply($model, RepositoryInterface $repository)
    {
        return QueryBuilder::for($model)
            ->defaultSort('-id')
            ->allowedFields(array_merge(
                $this->allowedModelFields(),
            ))
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::partial('name'),
                AllowedFilter::partial('code'),
                AllowedFilter::partial('created_at'),
                AllowedFilter::partial('updated_at'),
                AllowedFilter::trashed(),
            ])
            ->allowedSorts([
                'id',
                'name',
                'code',
                'created_at',
                'updated_at',
                'deleted_at',
            ]);
    }
}
