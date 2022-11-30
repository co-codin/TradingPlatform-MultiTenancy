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
        DefaultRole::ADMIN,
        'Brand Admin',
        'Brand Manager',
        'Desk Admin',
        'Compliance',
        'Conversion Manager',
        'Conversion Agent',
        'Retention Manager',
        'Affiliate',
        'Affiliate Manager',
        'Support',
        'IT',
    ];

    public function run()
    {
        $this->seedRoles();
    }

    protected function seedRoles()
    {
        foreach ($this->roles as $role)
        {
             Role::query()->firstOrCreate([
                'name' => $role,
                'guard_name' => 'api',
            ]);
        }
    }
}
