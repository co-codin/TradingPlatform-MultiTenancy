<?php

declare(strict_types=1);

namespace Modules\Role\Services;

use Illuminate\Support\Arr;
use Modules\Role\Dto\PermissionDto;
use Modules\Role\Dto\PermissionDto;
use Modules\Role\Models\Permission;
use Modules\Role\Models\Role;
use Spatie\Permission\PermissionRegistrar;

final class PermissionStorage
{
    /**
     * Store permission.
     *
     * @param PermissionDto $dto
     * @return Permission
     */
    final public function store(PermissionDto $dto): Permission
    {
        $permission = Permission::query()->create($dto->toArray());

        if (! $permission->save()) {
            throw new \LogicException('Не удалось сохранить Роль');
        }

        $this->clearCache();

        return $permission;
    }

    /**
     * Update permission.
     *
     * @param Role $role
     * @param PermissionDto $dto
     * @return Role
     */
    final public function update(Role $role, PermissionDto $dto): Role
    {
        if (!$role->update(Arr::only($dto->toArray(),
            ['name', 'key']
        ))) {
            throw new \LogicException('Не удалось изменить данные Роли');
        }

        $this->clearCache();

        return $role;
    }

    /**
     * Delete role.
     *
     * @param Role $role
     * @return void
     */
    final public function delete(Role $role): void
    {
        if (!$role->delete()) {
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
