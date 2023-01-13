<?php

declare(strict_types=1);

namespace Modules\Customer\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Customer\Models\Customer;
use Modules\Sale\Http\Resources\SaleStatusResource;

/**
 * @OA\Schema (
 *     schema="AffiliateCustomer",
 *     type="object",
 *     required={
 *         "id",
 *         "email",
 *     },
 *     @OA\Property(property="id", type="integer", description="Worker ID"),
 *     @OA\Property(property="email", type="string", format="email", description="Email"),
 *     @OA\Property(property="is_ftd", type="boolean", description="Is FTD", nullable="true"),
 *     @OA\Property(property="first_deposit_date", type="string", format="date-time", description="First deposit date", nullable="true", example="2022-12-17 08:44:09"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Date and time of creation", example="2022-12-17 08:44:09"),
 *     @OA\Property(property="conversion_sale_status", type="object", ref="#/components/schemas/SaleStatusResource", description="Conversion sale status"),
 * ),
 *
 * @OA\Schema (
 *     schema="AffiliateCustomerCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/AffiliateCustomer")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         ref="#/components/schemas/Meta"
 *     )
 * ),
 *
 * @OA\Schema (
 *     schema="AffiliateCustomerResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/AffiliateCustomer"
 *     )
 * )
 *
 * Class CustomerResource
 * @mixin Customer
 */
final class AffiliateCustomerResource extends JsonResource
{
    /**
     * {@inheritDoc}
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'email' => $this->resource->email,
            'is_ftd' => $this->resource->is_ftd,
            'first_deposit_date' => $this->resource->first_deposit_date,
            'created_at' => $this->resource->created_at,
            'conversion_sale_status' => new SaleStatusResource($this->whenLoaded('conversionSaleStatus')),
        ];
    }
}
