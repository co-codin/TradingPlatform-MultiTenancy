<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\TelephonyProvider\Models\TelephonyProvider;
use Modules\User\Models\User;

final class TelephonyProviderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TelephonyProvider::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'is_default' => $this->faker->boolean,
            'user_id' => User::factory(),
        ];
    }
}
