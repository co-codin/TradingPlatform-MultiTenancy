<?php

declare(strict_types=1);

namespace Modules\Role\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\Brand\Models\Brand;
use Modules\Role\Enums\DefaultRole;
use Modules\Role\Models\Role;
use Modules\User\Models\User;

final class UserTestAdminSeeder extends Seeder
{
    private Brand $brand;

    public function __construct()
    {
        $this->brand = $this->createBrandForAdmin();
    }

    private function createBrandForAdmin(): Brand
    {
        $data = array_merge(Brand::factory()->make()->toArray(), [
            'name' => DefaultRole::ADMIN,
            'title' => DefaultRole::ADMIN,
            'database' => strtolower(DefaultRole::ADMIN),
            'domain' => strtolower(DefaultRole::ADMIN),
        ]);

        return Brand::query()->updateOrCreate($data);
    }

    public function run(): void
    {
        $this->createAdmin();
        $this->createWorkers();
        $this->createTestUsers();
    }

    private function createAdmin(): void
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

        $user->brands()->sync($this->brand);
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

        $userIds = [];

        foreach ($emails as $email) {
            $user = $this->getUserByEmail($email);
            $user->assignRole($roles);
            $userIds[] = $user->id;
        }

        $this->brand->users()->sync($userIds, false);
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

        $userIds = [];

        for ($i = 0; $i < 300; $i++) {
            $role = fake()->randomElement($roles);

            $user = User::factory()->create();
            $userIds[] = $user->id;

            $user->assignRole(
                Role::query()->firstOrCreate(
                    Role::factory()->raw([
                        'name' => $role,
                        'key' => Str::slug($role),
                    ])
                )
            );
        }

        $this->brand->users()->sync($userIds, false);
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
