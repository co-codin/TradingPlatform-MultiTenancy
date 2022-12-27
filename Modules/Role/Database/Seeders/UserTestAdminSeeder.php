<?php

declare(strict_types=1);

namespace Modules\Role\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\Role\Enums\DefaultRole;
use Modules\Role\Models\Role;
use Modules\User\Models\User;

final class UserTestAdminSeeder extends Seeder
{
    public function run(): void
    {
        $user = $this->getUserByEmail('admin@stoxtech.com');

        $user->assignRole([
            Role::query()->firstOrCreate(
                Role::factory()->raw([
                    'name' => DefaultRole::ADMIN,
                    'key' => strtolower(DefaultRole::ADMIN),
                ])
            ),
            Role::query()->firstOrCreate(
                Role::factory()->raw([
                    'name' => DefaultRole::ADMIN,
                    'key' => strtolower(DefaultRole::ADMIN),
                    'guard_name' => 'api',
                ])
            ),
        ]);

        $this->createWorkers();
        $this->createTestUsers();
    }

    private function createTestUsers(): void
    {
        $emails = [
            'qa@stoxtech.dev',
            'qa+worker@stoxtech.dev',
            'qa+worker1@stoxtech.dev',
            'qa+worker2@stoxtech.dev',
        ];

        $roles = Role::query()->firstOrCreate(
            Role::factory()->raw([
                'name' => 'Brand Admin',
                'key' => 'brand-admin',
            ]),
            Role::factory()->raw([
                'name' => 'Brand Admin',
                'key' => 'brand-admin',
                'guard_name' => 'api',
            ]),
        );

        foreach ($emails as $email) {
            $this->getUserByEmail($email)->assignRole($roles);
        }
    }

    public function createWorkers()
    {
        $roles = [
            DefaultRole::BRAND_MANAGER,
            DefaultRole::COMPLIANCE,
            DefaultRole::CONVERSION_MANAGER,
            DefaultRole::CONVERSION_AGENT,
            DefaultRole::RETENTION_AGENT,
            DefaultRole::AFFILIATE,
            DefaultRole::AFFILIATE_MANAGER,
            DefaultRole::SUPPORT,
            DefaultRole::IT,
        ];

        for ($i = 0; $i < 300; $i++) {
            $role = fake()->randomElement($roles);

            User::factory()->create()->assignRole(
                Role::query()->firstOrCreate(
                    Role::factory()->raw([
                        'name' => $role,
                        'key' => Str::slug($role),
                    ])
                )
            );
        }
    }

    private function getUserByEmail(string $email): User
    {
        $username = Str::before($email, '@');

        $user = User::query()
            ->where('email', $email)
            ->orWhere('username', $username)
            ->first();

        if (! $user) {
            $user = User::factory()->create([
                'username' => $username,
                'first_name' => Str::before($username, '@') ?: $username,
                'last_name' => Str::before($username, '@') ?: $username,
                'email' => $email,
                'password' => Hash::make('password'),
            ]);
        }

        return $user;
    }
}
