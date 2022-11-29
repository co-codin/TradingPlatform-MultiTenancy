<?php

declare(strict_types=1);

namespace Modules\Role\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Role\Models\Column;
use OverflowException;

final class ColumnsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        try {
            while (true) {
                Column::factory()->create();
            }
        } catch (OverflowException $e) {
            //
        }
    }
}
