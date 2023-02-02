<?php

declare(strict_types=1);

namespace Modules\Role\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Modules\Role\Enums\DefaultRole;
use Modules\Role\Models\Role;

final class RolesTableSeeder extends Seeder
{
    protected array $roles = [
        DefaultRole::BRAND_ADMIN,
        DefaultRole::BRAND_MANAGER,
        DefaultRole::DESK_ADMIN,
        DefaultRole::COMPLIANCE,
        DefaultRole::CONVERSION_MANAGER,
        DefaultRole::CONVERSION_AGENT,
        DefaultRole::RETENTION_AGENT,
        DefaultRole::AFFILIATE,
        DefaultRole::AFFILIATE_MANAGER,
        DefaultRole::SUPPORT,
        DefaultRole::IT,
    ];

    public function run(): void
    {
        $this->seedAdmin();
        $this->seedRoles();
    }

    private function seedAdmin(): void
    {
        $admin = [
            'name' => DefaultRole::ADMIN,
            'key' => Str::slug(DefaultRole::ADMIN),
            'is_default' => true,
            'guard_name' => 'web',
        ];
        Role::query()->firstOrCreate(Role::factory()->raw($admin));
        $admin['guard_name'] = 'api';
        Role::query()->firstOrCreate(Role::factory()->raw($admin));
    }

    private function seedRoles(): void
    {
        foreach ($this->roles as $role) {
            $data = [
                'name' => $role,
                'key' => Str::slug($role),
                'is_default' => true,
            ];
            Role::query()->firstOrCreate(Role::factory()->raw($data));
            $data['guard_name'] = 'api';
            Role::query()->firstOrCreate(Role::factory()->raw($data));
        }
    }
}
