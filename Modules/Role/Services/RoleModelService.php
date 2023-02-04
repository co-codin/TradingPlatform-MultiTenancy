<?php

declare(strict_types=1);

namespace Modules\Role\Services;

use App\Models\Model;
use Illuminate\Support\Collection;
use Modules\Brand\Models\Brand;
use Modules\Role\Models\Column;
use Modules\Role\Models\Role;

final readonly class RoleModelService
{
    public function __construct(private Role $role, private Model $model)
    {
    }

    public function getSelectedColumnNamesByAction(string $actionName): ?array
    {
        $permission = $this->model->permissions()->whereRelation('action', 'name', $actionName)->first(['id']);
        if (! $permission) {
            return null;
        }

        $columnsByPermission = $this->role->columnsByPermission($permission->id);
        Brand::checkCurrent()
            ? $columnsByPermission->wherePivot('brand_id', Brand::current()?->id)
            : $columnsByPermission->wherePivotNull('brand_id');

        return $columnsByPermission->get(['name'])->pluck('name')->toArray();
    }

    public function getSelectedActionNames(): array
    {
        $permissions = $this->role->permissions()->whereBelongsTo($this->model);
        Brand::checkCurrent()
            ? $permissions->wherePivot('brand_id', Brand::current()?->id)
            : $permissions->wherePivotNull('brand_id');

        return $permissions->get(['name'])->map(fn ($p) => head(explode(' ', $p->name)))->unique()->toArray();
    }

    public function getAvailableActions(): Collection
    {
        return $this->model->permissions()->get(['name'])
            ->map(fn ($p) => head(explode(' ', $p->name)))->unique();
    }

    public function getAvailableColumnNames(): array
    {
        return Column::all(['name'])->pluck('name')->toArray();
    }
}
