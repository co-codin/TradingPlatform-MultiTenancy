<?php

namespace Modules\Geo\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Country.
 *
 * @OA\Schema(
 *     schema="Country",
 *     title="Country",
 *     description="Country model",
 *     @OA\Xml(name="Country"),
 *     @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 *     @OA\Property(property="name", type="string", example="Russia"),
 *     @OA\Property(property="iso2", type="string", example="ru"),
 *     @OA\Property(property="iso3", type="string", example="rus"),
 *     @OA\Property(property="created_at", type="date-time", example="1970-01-01"),
 *     @OA\Property(property="updated_at", type="date-time", example="1970-01-01"),
 *     @OA\Property(property="deleted_at", type="date-time", example="1970-01-01"),
 * )
 */
class CountryResource extends JsonResource
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
