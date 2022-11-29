<?php

namespace Modules\Sale\Http\Resources;

use App\Http\Resources\BaseJsonResource;
use Modules\Sale\Models\SaleStatus;
use Illuminate\Http\Request;

/**
 * Sale.
 *
 * @OA\Schema(
 *     schema="Sale",
 *     title="Sale",
 *     description="SaleStatus model",
 *     required={
 *         "name",
 *         "title",
 *         "color",
 *     },
 *     @OA\Xml(name="Sale"),
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
 *     schema="SaleCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Sale")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         ref="#/components/schemas/Meta"
 *     )
 * ),
 *
 * @OA\Schema (
 *     schema="SaleResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/Sale"
 *     )
 * )
 *
 * Class SaleResource
 *
 * @package Modules\Sale\Http\Resources
 * @mixin SaleStatus
 */
class SaleResource extends BaseJsonResource
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
