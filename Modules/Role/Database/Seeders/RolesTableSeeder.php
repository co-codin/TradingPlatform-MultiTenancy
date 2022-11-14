<?php

namespace Modules\Role\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Brand\Enums\BrandPermission;
use Modules\Role\Enums\DefaultRole;
use Modules\Role\Models\Permission;
use Modules\Role\Models\Role;

class RolesTableSeeder extends Seeder
{
    protected array $roles = [
//        DefaultRole::ADMIN => '*',
        'Brand Admin' => [
            BrandPermission::VIEW_BRANDS,
            BrandPermission::CREATE_BRANDS,
            BrandPermission::EDIT_BRANDS,
            BrandPermission::DELETE_BRANDS,
        ],
//        'Brand Manager' => '*',
//        'Desk Admin' => '*',
//        'Compliance' => '*',
//        'Conversion Manager' => '*',
//        'Conversion Agent' => '*',
//        'Retention Manager' => '*',
//        'Affiliate' => '*',
//        'Affiliate Manager' => '*',
//        'Support' => '*',
//        'IT' => '*',
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

            $permissionRecords = Permission::query()->whereIn('name', $permissions)
                ->get()
                ->pluck('id')
                ->toArray()
            ;

            $role->permissions()->sync($permissionRecords);
        }
    }

    protected function prepareAllPermissions() : array
    {
        return Permission::all()->toArray();
    }
}
