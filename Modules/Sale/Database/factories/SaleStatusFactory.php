<?php

namespace Modules\Sale\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Sale\Models\SaleStatus;

class SaleStatusFactory extends Factory
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
        ];
    }
}

