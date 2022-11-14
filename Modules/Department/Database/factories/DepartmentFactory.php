<?php

namespace Modules\Department\Database\factories;

use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Department\Models\Department;

class DepartmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Department::class;

    /**
     * Define the model's default state.
     *
     * @return array
     * @throws Exception
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'title' => $this->faker->title,
            'is_active' => (bool) random_int(0, 1),
            'is_default' => (bool) random_int(0, 1),
        ];
    }
}

