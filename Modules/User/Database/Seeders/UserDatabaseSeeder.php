<?php

declare(strict_types=1);

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Role\Database\Seeders\UserTestAdminSeeder;

final class UserDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(
            UserTestAdminSeeder::class,
        );
    }
}
