<?php

declare(strict_types=1);

namespace Modules\User\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Schema;
use Modules\Brand\Models\Brand;
use Modules\User\Models\User;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema (
 *     schema="AuthUser",
 *     type="object",
 *     required={
 *         "id",
 *         "permissions",
 *         "roles",
 *     },
 *     @OA\Property(property="id", type="integer"),
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
 *     @OA\Property(
 *         property="notifications",
 *         description="User notifications",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             required={
 *                 "count",
 *                 "list",
 *             },
 *             @OA\Property(property="count", type="integer", description="Count of notifications"),
 *             @OA\Property(property="list", type="array", @OA\Items(type="string"), description="List of notifications"),
 *         ),
 *     ),
 *     @OA\Property(property="menu", type="array", @OA\Items(ref="#/components/schemas/UserMenu")),
 *     @OA\Property(property="worker_info", type="object", ref="#/components/schemas/WorkerInfo", description="Worker info"),
 * )
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
 *
 * @mixin User
 */
class AuthUserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'permissions' => $this->getPermissionsViaRoles()->pluck('name'),
            'roles' => $this->roles->map->only(['id', 'name', 'guard_name', 'key'])->values(),
            'notifications' => Brand::whereDatabase($request->header('tenant'))->exists() ? [
                'count' => $this->unreadNotifications->count(),
                'list' => $this->unreadNotifications,
            ] : [
                'count' => 0,
                'list' => [],
            ],
            'menu' => new UserMenuResource($this),
            'worker_info' => new WorkerInfoResource($this->whenLoaded('worker_info')),
        ];
    }
}
