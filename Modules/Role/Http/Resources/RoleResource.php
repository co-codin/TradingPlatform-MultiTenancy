<?php

namespace Modules\Role\Http\Resources;

use OpenApi\Annotations as OA;
use App\Http\Resources\BaseJsonResource;

/**
 * @OA\Schema (
 *     schema="Role",
 *     type="object"
 * )
 *
 * @OA\Schema (
 *     schema="RoleCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Role")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         ref="#/components/schemas/Meta"
 *     )
 * )
 */
class RoleResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            'permissions' => PermissionResource::collection($this->whenLoaded('permissions')),
        ]);
    }
}
