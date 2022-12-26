<?php

declare(strict_types=1);

namespace Modules\Transaction\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Transaction\Models\TransactionsMt5Type;

/**
 * @OA\Schema (
 *     schema="TransactionsMt5Type",
 *     type="object",
 *     required={
 *         "id",
 *         "name",
 *         "title",
 *         "mt5_id",
 *         "created_at",
 *         "updated_at",
 *     },
 *     @OA\Property(property="id", type="integer", description="Transaction MT5 type ID"),
 *     @OA\Property(property="name", type="string", description="Transaction MT5 type name"),
 *     @OA\Property(property="title", type="string", description="Transaction MT5 type title"),
 *     @OA\Property(property="mt5_id", type="string", description="Transaction MT5 type mt5_id"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Date and time of creation", example="2022-12-17 08:44:09"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Date and time of last update", example="2022-12-17 08:44:09"),
 *     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, description="Date and time of soft delete", example="2022-12-17 08:44:09"),
 * ),
 *
 * @OA\Schema (
 *     schema="TransactionsMt5TypeCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/TransactionsMt5Type")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         ref="#/components/schemas/Meta"
 *     )
 * ),
 *
 * @OA\Schema (
 *     schema="TransactionsMt5TypeResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/TransactionsMt5Type"
 *     )
 * )
 *
 * Class TransactionsMt5TypeResource
 * @mixin TransactionsMt5Type
 */
final class TransactionsMt5TypeResource extends JsonResource
{
    /**
     * {@inheritDoc}
     */
    public function toArray($request): array
    {
        return parent::toArray($request);
    }
}
