<?php

declare(strict_types=1);

namespace Modules\Campaign\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Campaign\Models\CampaignTransaction;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema (
 *     schema="CampaignTransaction",
 *     type="object",
 *     required={
 *         "id",
 *         "affiliate_id",
 *         "type",
 *         "amount",
 *         "customer_ids",
 *         "created_at",
 *         "updated_at",
 *     },
 *     @OA\Property(property="id", type="integer", description="Campaign transaction ID"),
 *     @OA\Property(property="affiliate_id", type="integer", description="Campaign transaction affiliate id"),
 *     @OA\Property(property="type", type="integer", description="Campaign transaction type (1-Correction, 2-Payment)", enum={1,2}),
 *     @OA\Property(property="amount", type="float", description="Campaign transaction amount"),
 *     @OA\Property(property="customer_ids", type="string", description="Campaign transaction customer_ids", example={1, 2, 3}),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Date and time of creation", example="2022-12-17 08:44:09"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Date and time of last update", example="2022-12-17 08:44:09"),
 * ),
 *
 * @OA\Schema (
 *     schema="CampaignTransactionCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/CampaignTransaction")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         ref="#/components/schemas/Meta"
 *     )
 * ),
 *
 * @OA\Schema (
 *     schema="CampaignTransactionResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/CampaignTransaction"
 *     )
 * )
 *
 * Class CampaignTransactionResource
 * @mixin CampaignTransaction
 */
final class CampaignTransactionResource extends JsonResource
{
    /**
     * {@inheritDoc}
     */
    public function toArray($request): array
    {
        return parent::toArray($request);
    }
}
