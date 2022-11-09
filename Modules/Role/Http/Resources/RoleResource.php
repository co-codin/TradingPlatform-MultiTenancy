<?php

namespace Modules\Role\Http\Resources;

use OpenApi\Annotations as OA;
use App\Http\Resources\BaseJsonResource;

/**
 * @OA\Schema (
 *     schema="Role",
 *     type="object"
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
