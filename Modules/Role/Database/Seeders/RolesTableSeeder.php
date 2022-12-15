<?php

declare(strict_types=1);

namespace Modules\Role\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Modules\Brand\Enums\BrandPermission;
use Modules\Role\Enums\DefaultRole;
use Modules\Role\Models\Permission;
use Modules\Role\Models\Role;

final class RolesTableSeeder extends Seeder
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
            Role::query()->firstOrCreate(Role::factory()->raw(['name' => $role, 'key' => Str::kebab($role)]));
        }
    }
}
