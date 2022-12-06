<?php

declare(strict_types=1);

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Role\Enums\DefaultRole;
use Modules\Role\Models\Role;
use Modules\User\Models\User;

final class UserDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'admin@stoxtech.com')->first() ?? User::factory()->create([
            'email' => 'admin@stoxtech.com',
            'password' => Hash::make('password'),
        ]);

        $role = Role::where('name', DefaultRole::ADMIN)->first() ?? Role::factory()->create([
            'name' => DefaultRole::ADMIN,
            'key' => DefaultRole::ADMIN,
        ]);

        $user->assignRole($role);
    }
}
