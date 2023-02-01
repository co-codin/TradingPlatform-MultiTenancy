<?php

namespace Modules\Department\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Department\Enums\DepartmentEnum;
use Modules\Department\Models\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        foreach (DepartmentEnum::getValues() as $name) {
            Department::query()->updateOrCreate(
                [
                    'name' => $name,
                ],
                [
                    'title' => ucfirst($name),
                    'is_active' => true,
                    'is_default' => false,
                ],
            );
        }
    }
}
