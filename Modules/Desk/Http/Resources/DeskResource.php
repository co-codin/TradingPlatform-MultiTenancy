<?php

declare(strict_types=1);

namespace Modules\Desk\Http\Resources;

use App\Http\Resources\BaseJsonResource;
use Modules\Desk\Models\Desk;
use Modules\Geo\Http\Resources\CountryResource;
use Modules\Language\Http\Resources\LanguageResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Desk",
 *     title="Desk",
 *     description="Desk model",
 *     required={
 *         "id",
 *         "name",
 *         "title",
 *         "is_active",
 *         "parent_id",
 *         "created_at",
 *         "updated_at",
 *     },
 *     @OA\Xml(name="Desk"),
 *     @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 *     @OA\Property(property="name", type="string", example="Any name"),
 *     @OA\Property(property="is_active", type="boolean", description="Activity of desk"),
 *     @OA\Property(property="parent_id", type="integer", description="Parent desk id"),
 *     @OA\Property(property="parent", type="object", ref="#/components/schemas/Desk"),
 *     @OA\Property(property="ancestors", type="array", @OA\Items(), example="[]", description="Array of Desk ancestors"),
 *     @OA\Property(property="descendants", type="array", @OA\Items(), example="[]", description="Array of Desk descendants"),
 *     @OA\Property(property="children", type="array", @OA\Items(), example="[]", description="Array of Desk children"),
 *     @OA\Property(property="languages", type="array", @OA\Items(ref="#/components/schemas/Language")),
 *     @OA\Property(property="countries", type="array", @OA\Items(ref="#/components/schemas/Country")),
 *     @OA\Property(property="created_at", type="string", format="date-time", readOnly="true",),
 *     @OA\Property(property="updated_at", type="string", format="date-time", readOnly="true",),
 * ),
 *
 * @OA\Schema (
 *     schema="DeskCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Desk")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         ref="#/components/schemas/Meta"
 *     )
 * ),
 *
 * @OA\Schema (
 *     schema="DeskResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/Desk"
 *     )
 * )
 * @mixin Desk
 */
final class DeskResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            'parent' => new self($this->whenLoaded('parent')),
            'ancestors' => self::collection($this->whenLoaded('ancestors')),
            'descendants' => self::collection($this->whenLoaded('descendants')),
            'children' => self::collection($this->whenLoaded('children')),
            'languages' => LanguageResource::collection($this->whenLoaded('languages')),
            'countries' => CountryResource::collection($this->whenLoaded('countries')),
        ]);
    }
}
