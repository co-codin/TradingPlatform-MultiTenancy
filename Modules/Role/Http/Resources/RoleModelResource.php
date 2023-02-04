<?php

declare(strict_types=1);

namespace Modules\Role\Http\Resources;

use App\Http\Resources\BaseJsonResource;
use App\Models\Action;
use App\Models\Model;
use Modules\Role\Models\Role;
use Modules\Role\Services\RoleModelService;
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
        $service = app(RoleModelService::class, ['role' => $this->role, 'model' => $this->resource]);

        $actions = $service->getAvailableActions();

        $array = parent::toArray($request);
        $explode = explode('\\', $array['name']);
        $array['name'] = end($explode);

        return [
            ...$array,
            'permissions_count' => $actions->count(),
            'available_actions' => $actions->toArray(),
            'selected_actions' => $service->getSelectedActionNames(),
            'available_columns' => $service->getAvailableColumnNames(),
            'selected_view_columns' => $service->getSelectedColumnNamesByAction(Action::NAMES['view']) ?? [],
            'selected_edit_columns' => $service->getSelectedColumnNamesByAction(Action::NAMES['edit']) ?? [],
        ];
    }
}
