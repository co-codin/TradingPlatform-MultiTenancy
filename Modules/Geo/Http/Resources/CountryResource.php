<?php

namespace Modules\Geo\Http\Resources;

use App\Http\Resources\BaseJsonResource;
use Illuminate\Http\Request;
use Modules\Geo\Models\Country;

/**
 * Country.
 *
 * @OA\Schema(
 *     schema="Country",
 *     title="Country",
 *     description="Country model",
 *     required={
 *         "id",
 *         "name",
 *         "iso2",
 *         "iso3",
 *         "created_at",
 *         "updated_at",
 *         "deleted_at",
 *     },
 *     @OA\Xml(name="Country"),
 *     @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 *     @OA\Property(property="name", type="string", example="Russia"),
 *     @OA\Property(property="iso2", type="string", example="RU"),
 *     @OA\Property(property="iso3", type="string", example="RUS"),
 *     @OA\Property(property="created_at", type="date-time", example="1970-01-01"),
 *     @OA\Property(property="updated_at", type="date-time", example="1970-01-01"),
 *     @OA\Property(property="deleted_at", type="date-time", example="1970-01-01", nullable=true),
 * ),
 *
 * @OA\Schema (
 *     schema="CountryCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Country")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         ref="#/components/schemas/Meta"
 *     )
 * ),
 *
 * @OA\Schema (
 *     schema="CountryResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/Country"
 *     )
 * )
 *
 * Class CountryResource
 *
 * @package Modules\Geo\Http\Resources
 * @mixin Country
 */
class CountryResource extends BaseJsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'iso2' => $this->resource->iso2,
            'iso3' => $this->resource->iso3,
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
            'deleted_at' => $this->resource->deleted_at,
        ];
    }
}
