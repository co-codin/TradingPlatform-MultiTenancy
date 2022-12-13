<?php

declare(strict_types=1);

namespace database\factories;

use App\Models\Action;
use Illuminate\Database\Eloquent\Factories\Factory;

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
