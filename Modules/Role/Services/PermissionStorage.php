<?php

declare(strict_types=1);

namespace Modules\Role\Services;

use Modules\Role\Dto\PermissionDto;
use Modules\Role\Models\Permission;
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
            throw new \LogicException(__('Can not create permission'));
        }

        $this->clearCache();

        return $permission;
    }

    /**
     * Update permission.
     *
     * @param Permission $permission
     * @param PermissionDto $dto
     * @return Permission
     */
    final public function update(Permission $permission, PermissionDto $dto): Permission
    {
        if (! $permission->update($dto->toArray())) {
            throw new \LogicException(__('Can not create permission'));
        }

        $this->clearCache();

        return $permission;
    }

    /**
     * Delete permission.
     *
     * @param Permission $permission
     * @return void
     */
    final public function delete(Permission $permission): void
    {
        if (! $permission->delete()) {
            throw new \LogicException('Can not delete permission');
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
