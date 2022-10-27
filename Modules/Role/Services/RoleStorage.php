<?php

namespace Modules\Role\Services;

use Illuminate\Support\Arr;
use Modules\Role\Dto\RoleDto;
use Modules\Role\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleStorage
{
    public function store(RoleDto $dto)
    {
        $role = new Role(Arr::only($dto->toArray(),
            ['name', 'key', 'guard_name']
        ));

        if (!$role->save()) {
            throw new \LogicException('Не удалось сохранить Роль');
        }

        if ($dto->permissions) {
            foreach ($dto->permissions as $permission) {
                $role->permissions()->attach($permission['id']);
            }
        }

        $this->clearCache();

        return $role;
    }

    public function update(Role $role, RoleDto $dto)
    {
        if (!$role->update(Arr::only($dto->toArray(),
            ['name', 'key']
        ))) {
            throw new \LogicException('Не удалось изменить данные Роли');
        }

        if ($dto->permissions) {
            $role->permissions()->detach();

            foreach ($dto->permissions as $permission) {
                $role->permissions()->attach($permission['id']);
            }

        }

        $this->clearCache();

        return $role;
    }

    public function updatePermissions(Role $role, array $permissions)
    {
        $role->permissions()->detach();

        foreach ($permissions as $permission) {
            $role->permissions()->attach($permission['id']);
        }

        $this->clearCache();
    }

    public function delete(Role $role)
    {
        if (!$role->delete()) {
            throw new \LogicException('can not delete role');
        }

        $this->clearCache();
    }

    protected function clearCache(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
