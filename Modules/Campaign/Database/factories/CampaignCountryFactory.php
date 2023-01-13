<?php

declare(strict_types=1);

namespace Modules\Campaign\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Campaign\Models\Campaign;
use Modules\Campaign\Models\CampaignCountry;
use Modules\Geo\Models\Country;
use Modules\User\Models\User;
use Spatie\Multitenancy\Landlord;
use Spatie\Multitenancy\Models\Tenant;

final class CampaignCountryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CampaignCountry::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'campaign_id' => Campaign::factory(),
            'country_id' => Country::inRandomOrder()->first()->id,
            'cpa' => $this->faker->randomFloat(2, 5, 30),
            'crg' => $this->faker->randomFloat(2, 5, 30),
            'working_days' => [
                1, 2, 3, 4, 5
            ],
            'working_hours' => Campaign::BASE_WORKING_HOURS,
            'daily_cap' => $this->faker->numberBetween(1, 10),
        ];
    }
}
