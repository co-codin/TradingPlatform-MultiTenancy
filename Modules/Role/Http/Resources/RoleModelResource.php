<?php

declare(strict_types=1);

namespace Modules\Role\Http\Resources;

use App\Http\Resources\BaseJsonResource;
use App\Models\Action;
use App\Models\Model;
use Modules\Role\Models\Column;
use Modules\Role\Models\Role;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema (
 *     schema="RoleModel",
 *     type="object",
 *     required={
 *         "id",
 *         "name",
 *         "permissions_count",
 *         "available_actions",
 *         "selected_actions",
 *         "available_columns",
 *         "selected_view_columns",
 *         "selected_edit_columns",
 *     },
 *     @OA\Property(property="id", type="integer", description="Model ID"),
 *     @OA\Property(property="name", type="string", description="Model name"),
 *     @OA\Property(property="permissions_count", type="integer", description="Model count of premissions"),
 *     @OA\Property(property="available_actions", type="array", description="List of available actions",
 *         @OA\Items(type="string", description="Action name"),
 *     ),
 *     @OA\Property(property="selected_actions", type="array", description="List of selected actions",
 *         @OA\Items(type="string", description="Action name"),
 *     ),
 *     @OA\Property(property="available_columns", type="array", description="List of available columns",
 *         @OA\Items(type="string", description="Column name"),
 *     ),
 *     @OA\Property(property="selected_view_columns", type="array", description="List of selected columns for view",
 *         @OA\Items(type="string", description="Column name"),
 *     ),
 *     @OA\Property(property="selected_edit_columns", type="array", description="List of selected columns for edit",
 *         @OA\Items(type="string", description="Column name"),
 *     ),
 * ),
 * @OA\Schema (
 *     schema="RoleModelCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/RoleModel")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         ref="#/components/schemas/Meta"
 *     )
 * ),
 * @OA\Schema (
 *     schema="RoleModelResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/RoleModel"
 *     )
 * )
 *
 * Class ModelResource
 *
 * @mixin Model
 */
final class RoleModelResource extends BaseJsonResource
{
    public Role $role;

    public function toArray($request): array
    {
        $array = parent::toArray($request);

        $explode = explode('\\', $array['name']);
        $array['name'] = end($explode);

        $viewPermission = $this->permissions()->whereRelation('action', 'name', Action::NAMES['view'])->first(['id']);
        if ($viewPermission) {
            $viewColumns = $this->role->columnsByPermission($viewPermission->id)->get(['name']);
        }
        $editPermission = $this->permissions()->whereRelation('action', 'name', Action::NAMES['edit'])->first(['id']);
        if ($editPermission) {
            $editColumns = $this->role->columnsByPermission($editPermission->id)->get(['name']);
        }

        $actions = $this->permissions()->get(['name'])->map(function ($permission) {
            $explode = explode(' ', $permission->name);
            $permission->name = array_shift($explode);

            return $permission->name;
        });
        $selectedActions = $this->role->permissions()->whereBelongsTo($this->resource)->get(['name'])
            ->map(function ($permission) {
                $explode = explode(' ', $permission->name);
                $permission->name = array_shift($explode);

                return $permission->name;
            });

        return array_merge($array, [
            'permissions_count' => $actions->count(),
            'available_actions' => $actions,
            'selected_actions' => $selectedActions,
            'available_columns' => Column::all(['name'])->pluck('name'),
            'selected_view_columns' => $viewColumns?->pluck('name') ?? [],
            'selected_edit_columns' => $editColumns?->pluck('name') ?? [],
        ]);
    }
}
