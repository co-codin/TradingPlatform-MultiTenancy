<?php

declare(strict_types=1);

namespace Modules\Role\Http\Resources;

use App\Http\Resources\BaseJsonResource;
use Modules\Role\Models\Role;

/**
 * @OA\Schema (
 *     schema="Role",
 *     type="object",
 *     required={
 *         "id",
 *         "name",
 *         "guard_name",
 *         "key",
 *         "created_at",
 *         "updated_at",
 *     },
 *     @OA\Property(property="id", type="integer", description="Role ID"),
 *     @OA\Property(property="name", type="string", description="User"),
 *     @OA\Property(property="guard_name", type="string", description="api"),
 *     @OA\Property(property="key", type="string", description="key"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Date and time of creation", example="1970-01-01 00:00:00"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Date and time of last update", example="1970-01-01 00:00:00"),
 *     @OA\Property(property="permissions", type="array", @OA\Items(ref="#/components/schemas/Permission"), description="Permission array"),
 * ),
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
 * ),
 *
 * @OA\Schema (
 *     schema="RoleResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/Role"
 *     )
 * )
 *
 * Class RoleResource
 *
 * @mixin Role
 */
final class RoleResource extends BaseJsonResource
{
    /**
     * {@inheritDoc}
     */
    final public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            'permissions' => PermissionResource::collection($this->whenLoaded('permissions')),
        ]);
    }
}
