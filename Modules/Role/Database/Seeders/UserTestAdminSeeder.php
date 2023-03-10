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
use Modules\User\Models\WorkerInfo;

final class UserTestAdminSeeder extends Seeder
{
    private Brand $brand;

    public function __construct()
    {
        $this->brand = $this->createBrandForAdmin();
    }

    public function run(): void
    {
        $this->createAdmin();
        $this->createWorkers();
        $this->createTestUsers();
    }

    public function createWorkers(): void
    {
        $roles = [
            DefaultRole::BRAND_MANAGER,
            DefaultRole::COMPLIANCE,
            DefaultRole::CONVERSION_MANAGER,
            DefaultRole::CONVERSION_AGENT,
            DefaultRole::RETENTION_MANAGER,
            DefaultRole::RETENTION_AGENT,
            DefaultRole::AFFILIATE,
            DefaultRole::AFFILIATE_MANAGER,
            DefaultRole::SUPPORT,
            DefaultRole::IT,
        ];

        $userIds = [];

        for ($i = 0; $i < 200; $i++) {
            $role = fake()->randomElement($roles);

            $user = User::factory()->create();
            $userIds[] = $user->id;

            $user->roles()->sync(Role::where('name', $role)->get());
        }

        $this->brand->users()->sync($userIds, false);
    }

    private function createBrandForAdmin(): Brand
    {
        return Brand::factory()->create([
            'name' => DefaultRole::ADMIN,
            'title' => DefaultRole::ADMIN,
            'database' => strtolower(DefaultRole::ADMIN),
            'domain' => strtolower(DefaultRole::ADMIN),
        ]);
    }

    private function createAdmin(): void
    {
        $user = $this->getUserByEmail('admin@stoxtech.com');

        $user->roles()->sync(Role::where('name', DefaultRole::ADMIN)->get());

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

        $roles = Role::where('name', DefaultRole::BRAND_ADMIN)->get();
        $userIds = [];

        foreach ($emails as $email) {
            $user = $this->getUserByEmail($email);
            $user->roles()->sync($roles);
            $userIds[] = $user->id;
        }

        $this->brand->users()->sync($userIds, false);
    }

    private function getUserByEmail(string $email): User
    {
        $username = Str::before($email, '@');

        $user = User::query()
            ->where('username', $username)
            ->first();

        if (! $user) {
            /** @var User $user */
            $user = User::factory()->create([
                'username' => $username,
                'password' => Hash::make('password'),
            ]);

            $this->brand->makeCurrent();

            if (! $user->workerInfo()->exists()) {
                $user->workerInfo()->create(WorkerInfo::factory()->raw([
                    'first_name' => $username,
                    'last_name' => $username,
                    'email' => $email,
                ]));
            }
        }

        return $user;
    }
}
