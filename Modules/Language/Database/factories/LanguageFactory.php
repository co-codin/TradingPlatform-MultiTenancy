<?php

declare(strict_types=1);

namespace Modules\Language\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Language\Models\Language;

final class LanguageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Language::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->country(),
            'code' => $this->faker->unique()->countryCode(),
        ];
    }
}
