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
        $user = $this->getUser('admin@stoxtech.com');

        $role = Role::query()->firstOrCreate(
            Role::factory()->raw([
                'name' => DefaultRole::ADMIN,
                'key' => strtolower(DefaultRole::ADMIN),
            ])
        );

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

        $role = Role::query()->firstOrCreate(
            Role::factory()->raw([
                'name' => 'Brand Admin',
                'key' => 'brand-admin',
            ])
        );

        foreach ($emails as $email) {
            $this->getUser($email)->assignRole($role);
        }
    }

    private function getUser(string $email): User
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
