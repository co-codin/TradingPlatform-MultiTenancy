<?php

declare(strict_types=1);

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;

final class UserDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('local', 'dev', 'development')) {
            $this->call(
                UserTestAdminSeeder::class,
            );
        }
    }
}
