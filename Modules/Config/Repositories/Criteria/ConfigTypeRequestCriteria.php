<?php

namespace Modules\Config\Repositories\Criteria;

use App\Http\Filters\LiveFilter;
use App\Repositories\Criteria\BaseCriteria;
use Prettus\Repository\Contracts\RepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ConfigTypeRequestCriteria extends BaseCriteria
{
    /**
     * @inheritDoc
     */
    public function apply($model, RepositoryInterface $repository)
    {
        return QueryBuilder::for($model)
            ->defaultSort('-id')
            ->allowedFields(static::allowedConfigTypeFields(),)
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::custom('live', new LiveFilter([
                    'id' => '=',
                ])),
                AllowedFilter::trashed(),
            ])
            ->allowedIncludes([
                'configType',
            ])
            ->allowedSorts([
                'id',
            ]);
    }

    public static function allowedConfigTypeFields($prefix = null): array
    {
        $fields = [

        ];

        if(!$prefix) {
            return $fields;
        }

        return array_map(fn($field) => $prefix . "." . $field, $fields);
    }
}
