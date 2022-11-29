<?php

declare(strict_types=1);

namespace Modules\Role\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Schema;
use Modules\Role\Models\Column;

final class ColumnFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Column::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique(maxRetries: 1)->randomElement($this->getUniqueColumnsFromSchema()),
        ];
    }

    private function getUniqueColumnsFromSchema(): array
    {
        $result = [];
        foreach (Schema::getAllTables() as $table) {
            $result = array_merge($result, Schema::getColumnListing($table->tablename));
        }

        return array_unique($result);
    }
}
