<?php

declare(strict_types=1);

namespace Modules\Campaign\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Campaign\Models\Campaign;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema (
 *     schema="Campaign",
 *     type="object",
 *     required={
 *         "id",
 *         "cpa",
 *         "working_hours",
 *         "daily_cap",
 *         "crg",
 *         "deleted_at",
 *         "created_at",
 *         "updated_at",
 *     },
 *     @OA\Property(property="id", type="integer", description="Campaign ID"),
 *     @OA\Property(property="cpa", type="float", description="Campaign cpa"),
 *     @OA\Property(property="working_hours", type="string", description="Campaign working hours by week days", example={"1":{"start":"10:00","end":"18:00"},"2":{"start":"10:00","end":"18:00"},"3":{"start":"10:00","end":"18:00"},"4":{"start":"10:00","end":"18:00"},"5":{"start":"10:00","end":"18:00"}}),
 *     @OA\Property(property="daily_cap", type="integer", description="Campaign daily cap"),
 *     @OA\Property(property="crg", type="float", description="Campaign crg"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Date and time of creation", example="2022-12-17 08:44:09"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Date and time of last update", example="2022-12-17 08:44:09"),
 *     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, description="Date and time of soft delete", example="2022-12-17 08:44:09"),
 * ),
 *
 * @OA\Schema (
 *     schema="CampaignCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Campaign")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         ref="#/components/schemas/Meta"
 *     )
 * ),
 *
 * @OA\Schema (
 *     schema="CampaignResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/Campaign"
 *     )
 * )
 *
 * Class CampaignResource
 * @mixin Campaign
 */
final class CampaignResource extends JsonResource
{
    /**
     * {@inheritDoc}
     */
    public function toArray($request): array
    {
        return array_merge(
            parent::toArray($request), [
                'working_hours' => $this->working_hours,
                'is_working_hours' => $this->isWorkingHours()
            ]);
    }
}
