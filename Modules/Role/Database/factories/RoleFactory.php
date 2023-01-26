<?php

declare(strict_types=1);

namespace Modules\Role\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
        $name = $this->faker->unique()->sentence(2);
        $guard = 'web';

        return [
            'name' => $name,
            'key' => Str::slug($name),
            'guard_name' => $guard,
            'is_default' => $this->faker->boolean(10),
        ];
    }
}
