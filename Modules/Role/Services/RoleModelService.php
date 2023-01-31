<?php

declare(strict_types=1);

namespace Modules\Role\Services;

use App\Models\Model;
use Illuminate\Support\Collection;
use Modules\Role\Models\Column;
use Modules\Role\Models\Role;

final readonly class RoleModelService
{
    public function __construct(private Role $role, private Model $model)
    {
    }

    public function getColumnNamesByAction(string $actionName): ?array
    {
        $permission = $this->model->permissions()->whereRelation('action', 'name', $actionName)->first(['id']);

        return $permission
            ? $this->role->columnsByPermission($permission->id)->get(['name'])->pluck('name')->toArray()
            : null;
    }

    public function getSelectedActionNames(): array
    {
        return $this->role->permissions()->whereBelongsTo($this->model)->get(['name'])
            ->map(function ($permission) {
                $explode = explode(' ', $permission->name);
                $permission->name = array_shift($explode);

                return $permission->name;
            })->toArray();
    }

    public function getAvailableActions(): Collection
    {
        return $this->model->permissions()->get(['name'])->map(function ($permission) {
            $explode = explode(' ', $permission->name);
            $permission->name = array_shift($explode);

            return $permission->name;
        });
    }

    public function getAvailableColumnNames(): array
    {
        return Column::all(['name'])->pluck('name')->toArray();
    }
}
