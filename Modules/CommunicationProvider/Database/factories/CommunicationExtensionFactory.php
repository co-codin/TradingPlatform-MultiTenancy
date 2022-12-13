<?php

declare(strict_types=1);

namespace Modules\CommunicationProvider\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\CommunicationProvider\Models\CommunicationExtension;
use Modules\CommunicationProvider\Models\CommunicationProvider;
use Modules\User\Models\User;

final class CommunicationExtensionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CommunicationExtension::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'provider_id' => CommunicationProvider::factory(),
            'user_id' => User::factory(),
        ];
    }
}
