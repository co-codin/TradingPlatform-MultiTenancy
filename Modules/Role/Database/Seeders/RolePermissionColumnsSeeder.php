<?php

declare(strict_types=1);

namespace Modules\Role\Database\Seeders;

use App\Models\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;
use Modules\Role\Models\Column;
use Modules\Role\Models\Permission;
use Modules\Role\Models\Role;

final class RolePermissionColumnsSeeder extends Seeder
{
    public function run(): void
    {
        /** @var Role[] $roles */
        $roles = Role::inRandomOrder()->get();

        /** @var Collection|Permission[] $permissions */
        $permissions = Permission::whereHas('action', function (Builder $query) {
            $query->whereIn('name', [Action::NAMES['view'], Action::NAMES['edit']]);
        })->get();

        $columns = Column::all();

        foreach ($roles as $role) {
            for ($i = 0; $i < 5; $i++) {
                $columnsToSync = [];
                $permission = $permissions->random();
                foreach ($columns->random(10) as $column) {
                    $columnsToSync[$column->id] = ['permission_id' => $permission->id];
                }
                $role->columnsByPermission($permission->id)->sync($columnsToSync);
            }
        }
    }
}
