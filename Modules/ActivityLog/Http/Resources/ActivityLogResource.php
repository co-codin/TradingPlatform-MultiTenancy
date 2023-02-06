<?php

declare(strict_types=1);

namespace Modules\ActivityLog\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\ActivityLog\Models\ActivityLog;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema (
 *     schema="ActivityLog",
 *     type="object",
 *     required={
 *         "id",
 *         "log_name",
 *         "description",
 *         "subject_type",
 *         "subject_id",
 *         "causer_type",
 *         "causer_id",
 *         "properties",
 *         "created_at",
 *         "updated_at",
 *         "event",
 *         "batch_uuid",
 *         "brand_id"
 *     },
 *     @OA\Property(property="id", type="integer", description="Activity log ID"),
 *     @OA\Property(property="log_name", type="string", description="Activity log name"),
 *     @OA\Property(property="description", type="string", description="Activity log description"),
 *     @OA\Property(property="subject_type", type="string", description="Activity log subject type"),
 *     @OA\Property(property="subject_id", type="integer", description="Activity log subject ID"),
 *     @OA\Property(property="causer_type", type="string", description="Activity log causer type"),
 *     @OA\Property(property="causer_id", type="integer", description="Activity log causer ID"),
 *     @OA\Property(property="properties", type="string", description="Activity log properties", example="{}"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Date and time of creation", example="2022-12-17 08:44:09"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Date and time of last update", example="2022-12-17 08:44:09"),
 *     @OA\Property(property="event", type="string", description="Activity log event"),
 *     @OA\Property(property="batch_uuid", type="string", description="Activity log uuid"),
 *     @OA\Property(property="brand_id", type="integer", description="Activity log brand ID"),
 * ),
 * @OA\Schema (
 *     schema="ActivityLogCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/ActivityLog")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         ref="#/components/schemas/Meta"
 *     )
 * ),
 * @OA\Schema (
 *     schema="ActivityLogResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/ActivityLog"
 *     )
 * )
 *
 * Class ActivityLogResource
 *
 * @mixin ActivityLog
 */
final class ActivityLogResource extends JsonResource
{
    /**
     * {@inheritDoc}
     */
    public function toArray($request): array
    {
        return parent::toArray($request);
    }
}
