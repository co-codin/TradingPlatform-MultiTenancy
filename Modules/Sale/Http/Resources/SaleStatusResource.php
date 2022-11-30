<?php

namespace Modules\Sale\Http\Resources;

use App\Http\Resources\BaseJsonResource;
use Modules\Sale\Models\SaleStatus;
use Illuminate\Http\Request;

/**
 * SaleStatus.
 *
 * @OA\Schema(
 *     schema="SaleStatus",
 *     title="SaleStatus",
 *     description="SaleStatus model",
 *     required={
 *         "name",
 *         "title",
 *         "color",
 *     },
 *     @OA\Xml(name="SaleStatus"),
 *     @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 *     @OA\Property(property="name", type="string", example="Any name"),
 *     @OA\Property(property="title", type="string", example="Any title"),
 *     @OA\Property(property="is_active", type="boolean", example="1"),
 *     @OA\Property(property="color", type="string", example="#e1e1e1"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true),
 * ),
 *
 * @OA\Schema (
 *     schema="SaleStatusCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/SaleStatus")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         ref="#/components/schemas/Meta"
 *     )
 * ),
 *
 * @OA\Schema (
 *     schema="SaleStatusResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/SaleStatus"
 *     )
 * )
 *
 * Class SaleStatusResource
 *
 * @package Modules\Sale\Http\Resources
 * @mixin SaleStatus
 */
class SaleStatusResource extends BaseJsonResource
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
            'title' => $this->resource->title,
            'color' => $this->resource->color,
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
            'deleted_at' => $this->resource->deleted_at,
        ];
    }
}
