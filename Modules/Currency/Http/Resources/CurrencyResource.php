<?php

declare(strict_types=1);

namespace Modules\User\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Currency\Models\Currency;
use Modules\Geo\Http\Resources\CountryResource;

/**
 * @OA\Schema (
 *     schema="Currency",
 *     type="object",
 *     required={
 *         "id",
 *         "name",
 *         "iso3",
 *         "symbol",
 *         "is_available",
 *         "created_at",
 *         "updated_at",
 *     },
 *     @OA\Property(property="id", type="integer", description="Currency ID"),
 *     @OA\Property(property="name", type="string", description="Currency name"),
 *     @OA\Property(property="iso3", type="string", description="Currency iso3"),
 *     @OA\Property(property="symbol", type="string", description="Currency symbol"),
 *     @OA\Property(property="is_available", type="boolean", description="Currency is available"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Date and time of creation", example="2022-12-17 08:44:09"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Date and time of last update", example="2022-12-17 08:44:09"),
 *     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, description="Date and time of soft delete", example="2022-12-17 08:44:09"),
 *     @OA\Property(property="countries", type="array", @OA\Items(ref="#/components/schemas/Country"), description="Array of countries"),
 * ),
 *
 * @OA\Schema (
 *     schema="CurrencyCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Currency")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         ref="#/components/schemas/Meta"
 *     )
 * ),
 *
 * @OA\Schema (
 *     schema="CurrencyResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/Currency"
 *     )
 * )
 *
 * Class CurrencyResource
 * @mixin Currency
 */
final class CurrencyResource extends JsonResource
{
    /**
     * {@inheritDoc}
     */
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            'countries' => CountryResource::collection($this->whenLoaded('countries')),
        ]);
    }
}
