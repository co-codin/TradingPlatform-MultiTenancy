<?php

namespace Modules\Role\Http\Resources;

use App\Http\Resources\BaseJsonResource;
use App\Models\Action;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema (
 *     schema="Action",
 *     type="object",
 *     required={
 *         "id",
 *         "name"
 *     },
 *     @OA\Property(property="id", type="integer", description="Action ID"),
 *     @OA\Property(property="name", type="string", description="Action name"),
 * ),
 * @OA\Schema (
 *     schema="ActionCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Action")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         ref="#/components/schemas/Meta"
 *     )
 * ),
 * @OA\Schema (
 *     schema="ActionResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/Action"
 *     )
 * )
 *
 * Class ActionResource
 *
 * @mixin Action
 */
class ActionResource extends BaseJsonResource
{
}
