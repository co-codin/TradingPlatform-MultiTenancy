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

    private static $position = 0;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $user_id = (User::first() ?? User::factory()->create())->id;

        if (self::$position == 0) {
            self::$position = Splitter::whereUserId($user_id)->whereIsActive(true)->max('position') ?? 0;
        }

        self::$position++;

        return [
            'user_id' => $user_id,
            'name' => $this->faker->sentence(3),
            'is_active' => true,
            'position' => self::$position,
        ];
    }
}
