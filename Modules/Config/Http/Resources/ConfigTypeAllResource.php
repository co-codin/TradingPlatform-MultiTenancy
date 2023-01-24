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
 *     schema="ConfigTypeAll",
 *     title="ConfigTypeAll",
 *     description="Config all type model",
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
 * ),
 *
 * @OA\Schema (
 *     schema="ConfigTypeAllCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/ConfigTypeAll")
 *     ),
 * ),
 *
 * @OA\Schema (
 *     schema="ConfigTypeAllResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/ConfigTypeAll"
 *     )
 * )
 *
 * Class ConfigResource
 *
 * @package Modules\Config\Http\Resources
 * @mixin ConfigType
 */
class ConfigTypeAllResource extends BaseJsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return parent::toArray($request);
    }
}
