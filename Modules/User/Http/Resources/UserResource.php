<?php

declare(strict_types=1);

namespace Modules\User\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Brand\Http\Resources\BrandResource;
use Modules\Department\Http\Resources\DepartmentResource;
use Modules\Desk\Http\Resources\DeskResource;
use Modules\Role\Http\Resources\PermissionResource;
use Modules\Role\Http\Resources\RoleResource;
use Modules\User\Models\User;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema (
 *     schema="Worker",
 *     type="object",
 *     required={
 *         "id",
 *         "username",
 *         "first_name",
 *         "last_name",
 *         "email",
 *         "is_active",
 *         "created_at",
 *         "updated_at",
 *     },
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="username", type="string"),
 *     @OA\Property(property="first_name", type="string"),
 *     @OA\Property(property="last_name", type="string"),
 *     @OA\Property(property="email", type="string", format="email"),
 *     @OA\Property(property="is_active", type="boolean"),
 *     @OA\Property(property="target", type="integer", nullable=true),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="last_login", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="permissions", type="array", @OA\Items(ref="#/components/schemas/Permission")),
 *     @OA\Property(property="roles", type="array", @OA\Items(ref="#/components/schemas/Role"))
 * ),
 *
 * @OA\Schema (
 *     schema="WorkerCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/User")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         ref="#/components/schemas/Meta"
 *     )
 * ),
 *
 * @OA\Schema (
 *     schema="UserResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/User"
 *     )
 * )
 *
 * Class UserResource
 * @package Modules\User\Http\Resources
 * @mixin User
 */
final class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'roles' => RoleResource::collection($this->whenLoaded('roles')),
            'permissions' => PermissionResource::collection($this->whenLoaded('permissions')),
            'brands' => BrandResource::collection($this->whenLoaded('brands')),
            'departments' => DepartmentResource::collection($this->whenLoaded('departments')),
            'desks' => DeskResource::collection($this->whenLoaded('desks')),
            'languages' => $this->whenLoaded('languages'),
        ]);
    }
}
