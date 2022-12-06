<?php

namespace Modules\Desk\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DeskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Desk\Models\Desk::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->sentence(2),
            'title' => $this->faker->sentence(10),
        ];
    }
}
