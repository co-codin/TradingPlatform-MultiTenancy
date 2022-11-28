<?php

namespace Modules\Role\Http\Resources;

use App\Http\Resources\BaseJsonResource;
use Modules\Role\Models\Permission;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema (
 *     schema="Permission",
 *     type="object",
 *     required={
 *         "id",
 *         "name",
 *         "guard_name",
 *         "model_id",
 *         "action_id",
 *         "created_at",
 *         "updated_at",
 *     },
 *     @OA\Property(property="id", type="integer", description="Role ID"),
 *     @OA\Property(property="name", type="string", description="User"),
 *     @OA\Property(property="guard_name", type="string", description="api"),
 *     @OA\Property(property="action_id", type="integer", description="Action ID"),
 *     @OA\Property(property="model_id", type="integer", description="Model ID"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Date and time of creation", example="1970-01-01 00:00:00"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Date and time of last update", example="1970-01-01 00:00:00"),
 * ),
 *
 * @OA\Schema (
 *     schema="PermissionCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Permission")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         ref="#/components/schemas/Meta"
 *     )
 * ),
 *
 * @OA\Schema (
 *     schema="PermissionResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/Permission"
 *     )
 * )
 *
 * Class PermissionResource
 *
 * @mixin Permission
 */
class PermissionResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            'action' => $this->whenLoaded('action'),
            'model' => $this->whenLoaded('model'),
            'columns' => $this->whenLoaded('columns'),
        ]);
    }
}
