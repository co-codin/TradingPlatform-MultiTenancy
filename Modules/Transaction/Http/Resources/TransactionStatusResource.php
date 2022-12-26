<?php

declare(strict_types=1);

namespace Modules\Transaction\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Transaction\Models\TransactionStatus;

/**
 * @OA\Schema (
 *     schema="TransactionStatus",
 *     type="object",
 *     required={
 *         "id",
 *         "name",
 *         "title",
 *         "is_active",
 *         "is_valid",
 *         "created_at",
 *         "updated_at",
 *     },
 *     @OA\Property(property="id", type="integer", description="Transaction status ID"),
 *     @OA\Property(property="name", type="string", description="Transaction status name"),
 *     @OA\Property(property="title", type="string", description="Transaction status title"),
 *     @OA\Property(property="is_active", type="boolean", description="Transaction status is active"),
 *     @OA\Property(property="is_valid", type="boolean", description="Transaction status is valid"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Date and time of creation", example="2022-12-17 08:44:09"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Date and time of last update", example="2022-12-17 08:44:09"),
 *     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, description="Date and time of soft delete", example="2022-12-17 08:44:09"),
 * ),
 *
 * @OA\Schema (
 *     schema="TransactionStatusCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/TransactionStatus")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         ref="#/components/schemas/Meta"
 *     )
 * ),
 *
 * @OA\Schema (
 *     schema="TransactionStatusResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/TransactionStatus"
 *     )
 * )
 *
 * Class TransactionStatusResource
 * @mixin TransactionStatus
 */
final class TransactionStatusResource extends JsonResource
{
    /**
     * {@inheritDoc}
     */
    public function toArray($request): array
    {
        return parent::toArray($request);
    }
}
