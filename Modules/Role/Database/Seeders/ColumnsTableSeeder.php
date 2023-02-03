<?php

declare(strict_types=1);

namespace Modules\Role\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
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
        $result = [];

        $landlordSchema = Schema::connection('landlord');
        foreach ($landlordSchema->getAllTables() as $table) {
            $result = array_merge($result, $landlordSchema->getColumnListing($table->tablename ?? $table->name));
        }

        $tenantSchema = Schema::connection('tenant');
        foreach ($tenantSchema->getAllTables() as $table) {
            $result = array_merge($result, $tenantSchema->getColumnListing($table->tablename ?? $table->name));
        }

        foreach (array_unique($result) as $columnName) {
            Column::query()->updateOrCreate(
                ['name' => $columnName],
            );
        }
    }
}
