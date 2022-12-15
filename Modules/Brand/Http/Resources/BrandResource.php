<?php

declare(strict_types=1);

namespace Modules\Brand\Http\Resources;

use App\Http\Resources\BaseJsonResource;
use Illuminate\Http\Request;
use Modules\Brand\Models\Brand;
use Modules\User\Http\Resources\UserResource;

/**
 * Brand.
 *
 * @OA\Schema(
 *     schema="Brand",
 *     title="Brand",
 *     description="Brand model",
 *     required={
 *         "id",
 *         "name",
 *         "title",
 *         "slug",
 *         "logo_url",
 *         "is_active",
 *         "created_at",
 *         "updated_at",
 *         "deleted_at",
 *     },
 *     @OA\Xml(name="Brand"),
 *     @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 *     @OA\Property(property="name", type="string", example="Brand name"),
 *     @OA\Property(property="title", type="string", example="Brand title"),
 *     @OA\Property(property="slug", type="string", example="brand-slug"),
 *     @OA\Property(property="logo_url", type="string", example="/logo-url"),
 *     @OA\Property(property="is_active", type="bool", example="true"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="workers", type="array", @OA\Items(ref="#/components/schemas/Worker"))
 * ),
 *
 * @OA\Schema (
 *     schema="BrandCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Brand")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         ref="#/components/schemas/Meta"
 *     )
 * ),
 *
 * @OA\Schema (
 *     schema="BrandResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/Brand"
 *     )
 * )
 *
 * Class BrandResource
 *
 * @package Modules\Brand\Http\Resources
 * @mixin Brand
 */
final class BrandResource extends BaseJsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request $request
     * @return array
     */
    final public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            'workers' => UserResource::collection($this->whenLoaded('users')),
        ]);
    }
}
