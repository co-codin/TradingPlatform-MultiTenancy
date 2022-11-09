<?php


namespace Modules\User\Http\Resources;

use OpenApi\Annotations as OA;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Role\Http\Resources\RoleResource;
use Modules\User\Models\User;

/**
 * @OA\Schema (
 *     schema="AuthUser",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="first_name", type="string"),
 *     @OA\Property(property="last_name", type="string"),
 *     @OA\Property(property="permissions", type="array", @OA\Items(type="string")),
 *     @OA\Property(property="role", type="object", ref="#/components/schemas/Role")
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
 * @OA\Schema (
 *     schema="AuthUserResponse",
 *     type="object",
 *     @OA\Property(
 *         property="user",
 *         type="object",
 *         ref="#/components/schemas/AuthUser"
 *     ),
 *     @OA\Property(property="token", type="string")
 * )
 *
 * Class AuthUserResource
 * @package Modules\User\Http\Resources
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
            'role' => new RoleResource($this->role),
        ];
    }
}