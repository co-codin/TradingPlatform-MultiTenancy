<?php

declare(strict_types=1);

namespace Modules\Transaction\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Transaction\Models\Wallet;

/**
 * @OA\Schema (
 *     schema="TransactionsWallet",
 *     type="object",
 *     required={
 *         "id",
 *         "name",
 *         "title",
 *         "mt5_id",
 *         "currency_id",
 *         "customer_id",
 *         "created_at",
 *         "updated_at",
 *     },
 *     @OA\Property(property="id", type="integer", description="Wallet ID"),
 *     @OA\Property(property="name", type="string", description="Wallet name"),
 *     @OA\Property(property="title", type="string", description="Wallet title"),
 *     @OA\Property(property="mt5_id", type="string", description="Wallet mt5_id"),
 *     @OA\Property(property="currency_id", type="integer", description="Wallet currency_id"),
 *     @OA\Property(property="customer_id", type="integer", description="Wallet customer_id"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Date and time of creation", example="2022-12-17 08:44:09"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Date and time of last update", example="2022-12-17 08:44:09"),
 *     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, description="Date and time of soft delete", example="2022-12-17 08:44:09"),
 * ),
 *
 * @OA\Schema (
 *     schema="TransactionsWalletCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/TransactionsWallet")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         ref="#/components/schemas/Meta"
 *     )
 * ),
 *
 * @OA\Schema (
 *     schema="TransactionsWalletResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/TransactionsWallet"
 *     )
 * )
 *
 * Class TransactionsWalletResource
 * @mixin Wallet
 */
final class WalletResource extends JsonResource
{
    /**
     * {@inheritDoc}
     */
    public function toArray($request): array
    {
        return parent::toArray($request);
    }
}
