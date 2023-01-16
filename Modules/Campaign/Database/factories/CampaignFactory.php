<?php

declare(strict_types=1);

namespace Modules\Campaign\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Campaign\Models\Campaign;
use Modules\Geo\Models\Country;
use Modules\User\Models\User;

final class CampaignFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Campaign::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $working_hours = [
            1 => ['start' => '10:00', 'end' => '18:00'],
            2 => ['start' => '10:00', 'end' => '18:00'],
            3 => ['start' => '10:00', 'end' => '18:00'],
            4 => ['start' => '10:00', 'end' => '18:00'],
            5 => ['start' => '10:00', 'end' => '18:00'],
        ];

        return [
            'affiliate_id' => (User::first() ?? User::factory()->create())->id,
            'name' => $this->faker->sentence(3),
            'cpa' => $this->faker->randomFloat(2, 5, 30),
            'working_hours' => $working_hours,
            'daily_cap' => $this->faker->numberBetween(1, 10),
            'crg' => $this->faker->randomFloat(2, 5, 30),
            'is_active' => $this->faker->boolean(),
            'balance' => $this->faker->randomFloat(2, 500, 3000),
            'monthly_cr' => $this->faker->numberBetween(1, 20),
            'monthly_pv' => $this->faker->numberBetween(1, 20),
            'crg_cost' => $this->faker->randomFloat(2, 10, 300),
            'ftd_cost' => $this->faker->randomFloat(2, 10, 300),
            'country_id' => (Country::first() ?? Country::factory()->create())->id,
        ];
    }
}
