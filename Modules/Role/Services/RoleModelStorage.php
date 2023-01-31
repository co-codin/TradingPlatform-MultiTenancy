<?php

declare(strict_types=1);

namespace Modules\Role\Services;

use App\Models\Action;
use App\Models\Model;
use Illuminate\Database\Eloquent\Collection;
use Modules\Role\Dto\RoleModelDto;
use Modules\Role\Models\Column;
use Modules\Role\Models\Permission;
use Modules\Role\Models\Role;

final readonly class RoleModelStorage
{
    private Collection $columns;

    public function __construct()
    {
        $this->columns = Column::all(['id', 'name']);
    }

    /**
     * Update role model.
     *
     * @param  Role  $role
     * @param  Model  $model
     * @param  RoleModelDto  $selected
     * @return void
     */
    public function update(Role $role, Model $model, RoleModelDto $selected): void
    {
        $role->permissions()->sync(
            Permission::query()->whereBelongsTo($model)
                ->whereHas('action', fn ($q) => $q->whereIn('name', $selected->selected_actions))
                ->get(['id'])
        );

        $this->syncRoleModelColumnsByAction($role, $model, $selected->selected_view_columns, Action::NAMES['view']);
        $this->syncRoleModelColumnsByAction($role, $model, $selected->selected_edit_columns, Action::NAMES['edit']);
    }

    /**
     * @param  Role  $role
     * @param  Model  $model
     * @param  array<string>  $columnNames
     * @param  string  $actionName
     * @return void
     */
    private function syncRoleModelColumnsByAction(
        Role $role,
        Model $model,
        array $columnNames,
        string $actionName
    ): void {
        $permission = Permission::query()->whereBelongsTo($model)
            ->whereRelation('action', 'name', $actionName)->first(['id']);
        if ($permission) {
            $editColumns = [];
            foreach ($columnNames as $name) {
                $column = $this->columns->where('name', $name)->first();
                $editColumns[$column->id] = ['permission_id' => $permission->id];
            }
            $role->columnsByPermission($permission->id)->sync($editColumns);
        }
    }
}
