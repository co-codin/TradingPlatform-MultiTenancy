<?php

declare(strict_types=1);

namespace Modules\Sale\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Department\Models\Department;
use Modules\Sale\Models\SaleStatus;

final class SaleStatusFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SaleStatus::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'title' => $this->faker->sentence(10),
            'color' => $this->faker->hexColor(),
            'department_id' => Department::factory(),
        ];
    }
}
