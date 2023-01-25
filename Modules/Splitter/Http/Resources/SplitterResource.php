<?php

declare(strict_types=1);

namespace Modules\Splitter\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Splitter\Models\Splitter;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema (
 *     schema="Splitter",
 *     type="object",
 *     required={
 *         "id",
 *         "user_id",
 *         "name",
 *         "is_active",
 *         "conditions",
 *         "share_conditions",
 *         "position",
 *         "deleted_at",
 *         "created_at",
 *         "updated_at",
 *     },
 *     @OA\Property(property="id", type="integer", description="Splitter ID"),
 *     @OA\Property(property="user_id", type="integer", description="Splitter user id"),
 *     @OA\Property(property="name", type="string", description="Splitter name"),
 *     @OA\Property(property="is_active", type="boolean", description="Splitter is active"),
 *     @OA\Property(property="conditions", type="string", description="Splitter conditions", example={}),
 *     @OA\Property(property="share_conditions", type="string", description="Splitter share conditions", example={}),
 *     @OA\Property(property="position", type="integer", description="Splitter position"),
 *     @OA\Property(property="deleted_at", type="string", format="date-time", description="Date and time of deleted", example="2022-12-17 08:44:09"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Date and time of creation", example="2022-12-17 08:44:09"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Date and time of last update", example="2022-12-17 08:44:09"),
 * ),
 * @OA\Schema (
 *     schema="SplitterCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Splitter")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         ref="#/components/schemas/Meta"
 *     )
 * ),
 * @OA\Schema (
 *     schema="SplitterResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/Splitter"
 *     )
 * )
 *
 * Class SplitterResource
 *
 * @mixin Splitter
 */
final class SplitterResource extends JsonResource
{
    /**
     * {@inheritDoc}
     */
    public function toArray($request): array
    {
        return parent::toArray($request);
    }
}
