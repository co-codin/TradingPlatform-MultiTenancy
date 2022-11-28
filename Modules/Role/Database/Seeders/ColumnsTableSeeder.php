<?php

declare(strict_types=1);

namespace Modules\Role\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Role\Models\Column;

final class ColumnsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Column::factory()->count(count(Column::NAMES))->create();
    }
}
