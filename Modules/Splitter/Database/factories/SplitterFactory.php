<?php

declare(strict_types=1);

namespace Modules\Splitter\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Splitter\Models\Splitter;
use Modules\User\Models\User;

final class SplitterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Splitter::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'user_id' => (User::first() ?? User::factory()->create())->id,
            'name' => $this->faker->sentence(3),
            'is_active' => $this->faker->boolean(),
        ];
    }
}
