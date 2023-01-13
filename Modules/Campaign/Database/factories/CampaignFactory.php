<?php

declare(strict_types=1);

namespace Modules\Campaign\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Campaign\Models\Campaign;
use Modules\User\Models\User;
use Spatie\Multitenancy\Landlord;
use Spatie\Multitenancy\Models\Tenant;

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
        $tenant = Tenant::current();

        $data = array_merge(
            $this->getTenantData(),
            Landlord::execute(function () {
                return $this->getLandlordData();
            }),
        );

        $tenant->makeCurrent();

        return $data;
    }

    /**
     * Get tenant data.
     *
     * @return array
     */
    private function getTenantData(): array
    {
        $working_hours = [
            1 => ['start' => '10:00', 'end' => '18:00'],
            2 => ['start' => '10:00', 'end' => '18:00'],
            3 => ['start' => '10:00', 'end' => '18:00'],
            4 => ['start' => '10:00', 'end' => '18:00'],
            5 => ['start' => '10:00', 'end' => '18:00'],
        ];

        return [
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
        ];
    }

    /**
     * Get landlord data.
     *
     * @return array
     */
    private function getLandlordData(): array
    {
        return [
            'affiliate_id' => User::factory(),
        ];
    }
}
