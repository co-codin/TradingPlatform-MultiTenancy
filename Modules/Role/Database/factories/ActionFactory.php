<?php

declare(strict_types=1);

namespace Modules\Role\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Role\Models\Action;

final class ActionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Action::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    final public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement(Action::NAMES),
        ];
    }
}
