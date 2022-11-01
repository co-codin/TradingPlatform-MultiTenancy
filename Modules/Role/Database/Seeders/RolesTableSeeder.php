<?php

namespace Modules\Role\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Role\Models\Permission;
use Modules\Role\Models\Role;

class RolesTableSeeder extends Seeder
{
    protected $roles = [
        'Администратор' => '*',
        'Продакт' => '*',
    ];

    public function run()
    {
        $this->seedRoles();
    }

    protected function seedRoles()
    {
        foreach ($this->roles as $name => $permissions)
        {
            $role = Role::query()->firstOrCreate([
                'name' => $name,
                'guard_name' => 'api',
            ]);

            $permissions = $permissions !== "*" ? $permissions : $this->prepareAllPermissions();

            $permissionRecords = Permission::whereIn('name', array_keys($permissions))
                ->get()
                ->toArray();

            $role->permissions()->sync($permissionRecords);
        }
    }

    protected function prepareAllPermissions() : array
    {
        return Permission::all()->toArray();
    }
}
