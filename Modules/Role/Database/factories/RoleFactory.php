<?php

declare(strict_types=1);

namespace Modules\Role\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

final class RoleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Role\Models\Role::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'key' => $this->faker->name,
            'guard_name' => 'web',
        ];
    }
}
