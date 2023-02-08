<?php

declare(strict_types=1);

namespace Modules\Role\Http\Resources;

use App\Http\Resources\BaseJsonResource;
use App\Models\Model;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema (
 *     schema="Model",
 *     type="object",
 *     required={
 *         "id",
 *         "name",
 *         "permissions_count",
 *     },
 *     @OA\Property(property="id", type="integer", description="Model ID"),
 *     @OA\Property(property="name", type="string", description="Model name"),
 *     @OA\Property(property="permissions_count", type="integer", description="Model count of premissions"),
 * ),
 * @OA\Schema (
 *     schema="ModelCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Model")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         ref="#/components/schemas/Meta"
 *     )
 * ),
 * @OA\Schema (
 *     schema="ModelResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/Model"
 *     )
 * )
 *
 * Class ModelResource
 *
 * @mixin Model
 */
final class ModelResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            'permissions_count' => $this->permissions()->count(),
            'permissions_by_total_count' => $this->resource->permissions_by_total_count,
        ]);
    }
}
