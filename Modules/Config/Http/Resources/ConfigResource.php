<?php

declare(strict_types=1);

namespace Modules\Config\Http\Resources;

use App\Http\Resources\BaseJsonResource;
use Illuminate\Http\Request;
use Modules\Config\Models\Config;

/**
 * Config.
 *
 * @OA\Schema(
 *     schema="Config",
 *     title="Config",
 *     description="Config model",
 *     required={
 *         "id",
 *         "config_type_id",
 *         "data_type",
 *         "name",
 *         "value",
 *         "description",
 *         "created_at",
 *         "updated_at",
 *     },
 *     @OA\Xml(name="Config"),
 *     @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 *     @OA\Property(property="name", type="string", example="Setting"),
 *     @OA\Property(property="config_type_id", type="integer", example="1"),
 *     @OA\Property(property="data_type", type="string", example="json"),
 *     @OA\Property(property="value", type="string", example="{}"),
 *     @OA\Property(property="description", type="string", description="Config description"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(property="configType", ref="#/components/schemas/ConfigType", description="Config type"),
 * ),
 * @OA\Schema (
 *     schema="ConfigCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Config")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         ref="#/components/schemas/Meta"
 *     )
 * ),
 * @OA\Schema (
 *     schema="ConfigResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/Config"
 *     )
 * )
 *
 * Class ConfigResource
 *
 * @mixin Config
 */
class ConfigResource extends BaseJsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            'configType' => new ConfigTypeResource($this->whenLoaded('configType')),
        ]);
    }
}
