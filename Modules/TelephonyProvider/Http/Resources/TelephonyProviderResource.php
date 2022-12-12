<?php

declare(strict_types=1);

namespace Modules\TelephonyProvider\Http\Resources;

use App\Http\Resources\BaseJsonResource;
use Illuminate\Http\Request;
use Modules\TelephonyProvider\Models\TelephonyProvider;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="TelephonyProvider",
 *     title="TelephonyProvider",
 *     description="TelephonyProvider model",
 *     required={
 *         "name",
 *         "title",
 *         "color",
 *     },
 *     @OA\Xml(name="TelephonyProvider"),
 *     @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 *     @OA\Property(property="name", type="string", example="Any name"),
 *     @OA\Property(property="is_default", type="boolean"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 * ),
 *
 * @OA\Schema (
 *     schema="TelephonyProviderCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/TelephonyProvider")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         ref="#/components/schemas/Meta"
 *     )
 * ),
 *
 * @OA\Schema (
 *     schema="TelephonyProviderResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/TelephonyProvider"
 *     )
 * )
 * @mixin TelephonyProvider
 */
class TelephonyProviderResource extends BaseJsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
        ];
    }
}
