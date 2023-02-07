<?php

namespace Modules\Geo\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Geo\Models\Country;

class CountryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Country::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->country,
            'iso2' => $this->faker->unique()->countryCode,
            'iso3' => $this->faker->unique()->countryISOAlpha3,
            'currency' => $this->faker->unique()->currencyCode,
            'is_forbidden' => false,
        ];
    }
}
