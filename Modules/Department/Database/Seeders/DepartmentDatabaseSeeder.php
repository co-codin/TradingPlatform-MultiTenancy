<?php

declare(strict_types=1);

namespace Modules\Department\Database\Seeders;

use Illuminate\Database\Seeder;

final class DepartmentDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call([
            DepartmentSeeder::class,
        ]);
    }
}
