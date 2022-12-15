<?php

declare(strict_types=1);

namespace Modules\Role\Services;

use Illuminate\Support\Arr;
use Modules\Role\Dto\RoleDto;
use Modules\Role\Models\Role;
use Spatie\Multitenancy\Models\Tenant;
use Spatie\Permission\PermissionRegistrar;

final class RoleStorage
{
    /**
     * Store role.
     *
     * @param  RoleDto  $dto
     * @return Role
     */
    final public function store(RoleDto $dto): Role
    {
        $role = new Role(Arr::only($dto->toArray(),
            ['name', 'key', 'guard_name']
        ));

        if (Tenant::checkCurrent()) {
            $role->brand()->associate(Tenant::current());
        }

        if (! $role->save()) {
            throw new \LogicException('Не удалось сохранить Роль');
        }

        if ($dto->permissions) {
            $this->updatePermissions($role, $dto->permissions);
        }

        $this->clearCache();

        return $role;
    }

    /**
     * Update role.
     *
     * @param  Role  $role
     * @param  RoleDto  $dto
     * @return Role
     */
    final public function update(Role $role, RoleDto $dto): Role
    {
        if (! $role->update(Arr::only($dto->toArray(),
            ['name', 'key']
        ))) {
            throw new \LogicException('Не удалось изменить данные Роли');
        }

        if ($dto->permissions) {
            $this->updatePermissions($role, $dto->permissions);
        }

        $this->clearCache();

        return $role;
    }

    /**
     * Update role permissions.
     *
     * @param  Role  $role
     * @param  array  $permissions
     * @return void
     */
    final public function updatePermissions(Role $role, array $permissions): void
    {
        $role->permissions()->detach();

        foreach ($permissions as $permission) {
            $role->permissions()->attach($permission['id']);
        }
    }

    /**
     * Delete role.
     *
     * @param  Role  $role
     * @return void
     */
    final public function delete(Role $role): void
    {
        if (! $role->delete()) {
            throw new \LogicException('can not delete role');
        }

        $this->clearCache();
    }

    /**
     * Clear cache.
     *
     * @return void
     */
    final protected function clearCache(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
