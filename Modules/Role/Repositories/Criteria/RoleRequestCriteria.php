<?php

declare(strict_types=1);

namespace Modules\Role\Repositories\Criteria;

use App\Repositories\Criteria\BaseCriteria;
use Modules\Brand\Models\Brand;
use Prettus\Repository\Contracts\RepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class RoleRequestCriteria extends BaseCriteria
{
    /**
     * {@inheritdoc}
     */
    protected static array $allowedModelFields = [
        'id',
        'name',
        'key',
        'guard_name',
        'is_default',
    ];

    public function apply($model, RepositoryInterface $repository)
    {
        $builder = QueryBuilder::for($model)->defaultSort('-id')
            ->allowedFields(self::$allowedModelFields)
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::partial('name'),
                AllowedFilter::exact('key'),
                AllowedFilter::exact('is_default'),
                AllowedFilter::exact('guard_name')->default('web'),
            ])
            ->allowedSorts(['name', 'key', 'id', 'is_default'])
            ->allowedIncludes([
                'permissions',
                'users',
                'usersCount',
                'permissionsCount',
            ]);

        $builder->whereNull('brand_id');
        if (Brand::checkCurrent()) {
            $builder->orWhere('brand_id', Brand::current()?->id);
        }

        return $builder;
    }
}
