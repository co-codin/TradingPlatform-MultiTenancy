<?php

declare(strict_types=1);

namespace Modules\User\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\User\Models\User;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema (
 *     schema="AuthUser",
 *     type="object",
 *     required={
 *         "id",
 *         "first_name",
 *         "last_name",
 *         "permissions",
 *         "roles",
 *     },
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="first_name", type="string"),
 *     @OA\Property(property="last_name", type="string"),
 *     @OA\Property(property="permissions", type="array", @OA\Items(type="string")),
 *     @OA\Property(
 *         property="roles",
 *         description="Array of roles",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             required={
 *                 "id",
 *                 "name",
 *                 "key",
 *                 "guard_name",
 *             },
 *             @OA\Property(property="id", type="integer", description="Role ID"),
 *             @OA\Property(property="name", type="string", description="Name of role"),
 *             @OA\Property(property="key", type="string", description="Constant key of role"),
 *             @OA\Property(property="guard_name", type="string", description="Name of auth guard"),
 *         ),
 *     ),
 * )
 *
 * @OA\Schema (
 *     schema="AuthUserCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/AuthUser")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         ref="#/components/schemas/Meta"
 *     )
 * )
 *
 * @OA\Schema (
 *     schema="AuthUserResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/AuthUser"
 *     )
 * )
 *
 * Class AuthUserResource
 * @mixin User
 */
class AuthUserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'permissions' => $this->getPermissionsViaRoles()->pluck('name'),
            'roles' => $this->roles->map->only(['id', 'name', 'guard_name', 'key'])->values(),
        ];
    }
}
