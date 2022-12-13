<?php

declare(strict_types=1);

namespace Modules\User\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Brand\Http\Resources\BrandResource;
use Modules\Communication\Http\Resources\CommentResource;
use Modules\Department\Http\Resources\DepartmentResource;
use Modules\Desk\Http\Resources\DeskResource;
use Modules\Geo\Http\Resources\CountryResource;
use Modules\Language\Http\Resources\LanguageResource;
use Modules\Role\Http\Resources\PermissionResource;
use Modules\Role\Http\Resources\RoleResource;
use Modules\User\Models\User;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema (
 *     schema="Worker",
 *     type="object",
 *     required={
 *         "id",
 *         "username",
 *         "first_name",
 *         "last_name",
 *         "is_active",
 *         "created_at",
 *         "updated_at",
 *     },
 *     @OA\Property(property="id", type="integer", description="Worker ID"),
 *     @OA\Property(property="username", type="string", description="Worker username"),
 *     @OA\Property(property="first_name", type="string", description="First name"),
 *     @OA\Property(property="last_name", type="string", description="Last name"),
 *     @OA\Property(property="email", type="string", format="email", description="Email"),
 *     @OA\Property(property="is_active", type="boolean", description="Worker activity flag"),
 *     @OA\Property(property="target", type="integer", nullable=true, description="Target amount for the worker"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Date and time of creation", example="2022-12-17 08:44:09"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Date and time of last update", example="2022-12-17 08:44:09"),
 *     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, description="Date and time of soft delete", example="2022-12-17 08:44:09"),
 *     @OA\Property(property="last_login", type="string", format="date-time", nullable=true, description="Date and time of last login", example="2022-12-17 08:44:09"),
 *     @OA\Property(property="permissions", type="array", @OA\Items(ref="#/components/schemas/Permission"), description="Permission array"),
 *     @OA\Property(property="roles", type="array", @OA\Items(ref="#/components/schemas/Role"), description="Array of roles"),
 *     @OA\Property(property="affiliate_id", type="integer", description="Affiliate worker ID"),
 *     @OA\Property(property="show_on_scoreboards", type="boolean", description="Show on scoreboards"),
 * ),
 *
 * @OA\Schema (
 *     schema="WorkerCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Worker")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         ref="#/components/schemas/Meta"
 *     )
 * ),
 *
 * @OA\Schema (
 *     schema="WorkerResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/Worker"
 *     )
 * )
 *
 * Class WorkerResource
 * @mixin User
 */
final class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'roles' => RoleResource::collection($this->whenLoaded('roles')),
            'permissions' => PermissionResource::collection($this->whenLoaded('permissions')),
            'brands' => BrandResource::collection($this->whenLoaded('brands')),
            'departments' => DepartmentResource::collection($this->whenLoaded('departments')),
            'desks' => DeskResource::collection($this->whenLoaded('desks')),
            'languages' => LanguageResource::collection($this->whenLoaded('languages')),
            'display_options' => DisplayOptionResource::collection($this->whenLoaded('displayOptions')),
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
            'countries' => CountryResource::collection($this->whenLoaded('countries')),
        ]);
    }
}
