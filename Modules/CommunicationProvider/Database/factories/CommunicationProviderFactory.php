<?php

declare(strict_types=1);

namespace Modules\CommunicationProvider\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\CommunicationProvider\Models\CommunicationProvider;

final class CommunicationProviderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CommunicationProvider::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->word,
        ];
    }
}
