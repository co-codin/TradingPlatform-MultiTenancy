<?php

declare(strict_types=1);

namespace Modules\Language\Http\Resources;

use App\Http\Resources\BaseJsonResource;
use Illuminate\Http\Request;
use Modules\Language\Models\Language;

/**
 * Language.
 *
 * @OA\Schema(
 *     schema="Language",
 *     title="Language",
 *     description="Language model",
 *     required={
 *         "id",
 *         "name",
 *         "iso2",
 *         "iso3",
 *         "created_at",
 *         "updated_at",
 *         "deleted_at",
 *     },
 *     @OA\Xml(name="Language"),
 *     @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 *     @OA\Property(property="name", type="string", example="Russian"),
 *     @OA\Property(property="code", type="string", example="rus"),
 *     @OA\Property(property="created_at", type="date-time"),
 *     @OA\Property(property="updated_at", type="date-time"),
 *     @OA\Property(property="deleted_at", type="date-time", nullable=true),
 * ),
 *
 * @OA\Schema (
 *     schema="LanguageCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Language")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         ref="#/components/schemas/Meta"
 *     )
 * ),
 *
 * @OA\Schema (
 *     schema="LanguageResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/Language"
 *     )
 * )
 *
 * Class LanguageResource
 *
 * @package Modules\Language\Http\Resources
 * @mixin Language
 */
class LanguageResource extends BaseJsonResource
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
            'code' => $this->resource->code,
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
            'deleted_at' => $this->resource->deleted_at,
        ];
    }
}
