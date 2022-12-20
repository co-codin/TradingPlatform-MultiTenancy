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
        DefaultRole::ADMIN,
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

    public function run()
    {
        $this->seedRoles();
    }

    protected function seedRoles()
    {
        foreach ($this->roles as $role) {
            Role::query()->firstOrCreate(Role::factory()->raw(['name' => $role, 'key' => Str::kebab($role)]));
        }
    }
}
