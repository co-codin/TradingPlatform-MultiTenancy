<?php

declare(strict_types=1);

namespace Modules\Config\Http\Resources;

use App\Http\Resources\BaseJsonResource;
use Illuminate\Http\Request;
use Modules\Config\Models\ConfigType;

/**
 * ConfigType.
 *
 * @OA\Schema(
 *     schema="ConfigType",
 *     title="ConfigType",
 *     description="Config type model",
 *     required={
 *         "id",
 *         "name",
 *         "created_at",
 *         "updated_at",
 *     },
 *     @OA\Xml(name="ConfigType"),
 *     @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 *     @OA\Property(property="name", type="string", example="Name"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(property="configs", type="array", @OA\Items(ref="#/components/schemas/ConfigCollection"), description="Array of configs")
 * ),
 *
 * @OA\Schema (
 *     schema="ConfigTypeCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/ConfigType")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         ref="#/components/schemas/Meta"
 *     )
 * ),
 *
 * @OA\Schema (
 *     schema="ConfigTypeResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/ConfigType"
 *     )
 * )
 *
 * Class ConfigResource
 *
 * @package Modules\Config\Http\Resources
 * @mixin ConfigType
 */
class ConfigTypeResource extends BaseJsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            'configs' => ConfigResource::collection($this->whenLoaded('configs')),
        ]);
    }
}
