<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\TelephonyProvider\Models\TelephonyExtension;
use Modules\TelephonyProvider\Models\TelephonyProvider;

final class TelephonyExtensionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TelephonyExtension::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'provider_id' => TelephonyProvider::factory(),
        ];
    }
}
