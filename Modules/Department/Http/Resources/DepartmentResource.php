<?php

namespace Modules\Department\Http\Resources;

use App\Http\Resources\BaseJsonResource;
use Illuminate\Http\Request;
use Modules\Department\Models\Department;

/**
 * Department.
 *
 * @OA\Schema(
 *     schema="Department",
 *     title="Department",
 *     description="Department model",
 *     required={
 *         "id",
 *         "name",
 *         "title",
 *         "is_active",
 *         "is_default",
 *         "created_at",
 *         "updated_at",
 *         "deleted_at",
 *     },
 *     @OA\Xml(name="Department"),
 *     @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 *     @OA\Property(property="name", type="string", example="Name"),
 *     @OA\Property(property="title", type="string", example="Title"),
 *     @OA\Property(property="is_active", type="boolean", example=1),
 *     @OA\Property(property="is_default", type="boolean", example=1),
 *     @OA\Property(property="created_at", type="date-time", example="1970-01-01"),
 *     @OA\Property(property="updated_at", type="date-time", example="1970-01-01"),
 *     @OA\Property(property="deleted_at", type="date-time", example="1970-01-01", nullable=true),
 * ),
 *
 * @OA\Schema (
 *     schema="DepartmentCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Department")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         ref="#/components/schemas/Meta"
 *     )
 * ),
 *
 * @OA\Schema (
 *     schema="DepartmentResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/Department"
 *     )
 * )
 *
 * Class DepartmentResource
 *
 * @package Modules\Geo\Http\Resources
 * @mixin Department
 */
class DepartmentResource extends BaseJsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'title' => $this->resource->title,
            'is_active' => $this->resource->is_active,
            'is_default' => $this->resource->is_default,
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
            'deleted_at' => $this->resource->deleted_at,
        ];
    }
}
