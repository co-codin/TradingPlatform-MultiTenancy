<?php

namespace Modules\User\Repositories\Relationships;

use Modules\Brand\Models\Brand;
use Spatie\QueryBuilder\Includes\IncludeInterface;
use Illuminate\Database\Eloquent\Builder;
class DeskInclude implements IncludeInterface
{
    public function __invoke(Builder $query, string $include)
    {
        if (!request()->header('tenant')) {
            return response()->json(['message' => 'Tenant is not set.'], 403);
        }
        Brand::query()->where('database', request()->header('tenant'))->makeCurrent();

        $query->with($include);
    }
}
