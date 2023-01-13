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
 *         "name",
 *         "cpa",
 *         "working_hours",
 *         "daily_cap",
 *         "crg",
 *         "is_active",
 *         "balance",
 *         "monthly_cr",
 *         "monthly_pv",
 *         "crg_cost",
 *         "ftd_cost",
 *         "created_at",
 *         "updated_at",
 *     },
 *     @OA\Property(property="id", type="integer", description="Campaign ID"),
 *     @OA\Property(property="name", type="string", description="Campaign name"),
 *     @OA\Property(property="cpa", type="float", description="Campaign cpa"),
 *     @OA\Property(property="working_hours", type="string", description="Campaign working hours by week days", example={"1":{"start":"10:00","end":"18:00"},"2":{"start":"10:00","end":"18:00"},"3":{"start":"10:00","end":"18:00"},"4":{"start":"10:00","end":"18:00"},"5":{"start":"10:00","end":"18:00"}}),
 *     @OA\Property(property="daily_cap", type="integer", description="Campaign daily cap"),
 *     @OA\Property(property="crg", type="float", description="Campaign crg"),
 *     @OA\Property(property="is_active", type="boolean", description="Campaign is active"),
 *     @OA\Property(property="balance", type="float", description="Campaign balance"),
 *     @OA\Property(property="monthly_cr", type="integer", description="Campaign monthly cr"),
 *     @OA\Property(property="monthly_pv", type="integer", description="Campaign monthly pv"),
 *     @OA\Property(property="crg_cost", type="float", description="Campaign crg cost"),
 *     @OA\Property(property="ftd_cost", type="float", description="Campaign ftd cost"),
 *     @OA\Property(property="country_id", type="integer", description="Campaign country ID"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Date and time of creation", example="2022-12-17 08:44:09"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Date and time of last update", example="2022-12-17 08:44:09"),
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
                'is_working_hours' => $this->isWorkingHours(),
                'countries' => $this->whenLoaded('countries'),
            ]);
    }
}
