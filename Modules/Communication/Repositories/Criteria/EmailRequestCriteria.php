<?php

namespace Modules\Communication\Repositories\Criteria;

use App\Repositories\Criteria\BaseCriteria;
use Modules\User\Repositories\Criteria\UserRequestCriteria;
use Prettus\Repository\Contracts\RepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class EmailRequestCriteria extends BaseCriteria
{
    protected static array $allowedModelFields = [
        'id',
        'email_template_id',
        'subject',
        'body',
        'sent_by_system',
        'user_id',
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
                AllowedFilter::exact('email_template_id'),
                AllowedFilter::partial('subject'),
                AllowedFilter::partial('body'),
                AllowedFilter::partial('sent_by_system'),
                AllowedFilter::partial('user_id'),
                AllowedFilter::trashed(),
            ])
            ->allowedIncludes([
                'user',
            ])
            ->allowedSorts([
                'id', 'email_template_id', 'subject', 'body', 'sent_by_system', 'user_id', 'created_at', 'updated_at', 'deleted_at',
            ]);
    }
}
