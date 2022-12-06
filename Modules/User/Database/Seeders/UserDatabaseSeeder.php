<?php

declare(strict_types=1);

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\Role\Enums\DefaultRole;
use Modules\Role\Models\Role;
use Modules\User\Models\User;

final class UserDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'admin@stoxtech.com')->first() ?? User::factory()->create([
            'username' => 'admin',
            'first_name' => 'admin',
            'last_name' => 'admin',
            'email' => 'admin@stoxtech.com',
            'password' => Hash::make('password'),
        ]);

        $role = Role::query()->firstOrCreate(Role::factory()->raw([
            'name' => DefaultRole::ADMIN,
            'key' => strtolower(DefaultRole::ADMIN),
        ]));

        $user->assignRole($role);

        $this->createTestUsers();
    }

    private function createTestUsers(): void
    {
        $emails = [
            "qa@stoxtech.dev",
            "qa+worker@stoxtech.dev",
            "qa+worker1@stoxtech.dev",
            "qa+worker2@stoxtech.dev",
        ];

        $role = Role::query()->firstOrCreate(Role::factory()->raw([
            'name' => 'Brand Admin',
            'key' => 'brand-admin',
        ]));

        foreach ($emails as $email) {
            $username = Str::before($email, '@');
            $user = User::factory()->create([
                'username' => $username,
                'first_name' => Str::before($username, '+') ?: $username,
                'last_name' => Str::after($username, '+') ?: $username,
                'email' => $email,
                'password' => Hash::make('password'),
            ]);
            $user->assignRole($role);
        }
    }
}
