<?php

declare(strict_types=1);

namespace Modules\Campaign\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Modules\Brand\Models\Brand;
use Modules\Campaign\Enums\CampaignTransactionType;
use Modules\Campaign\Models\CampaignTransaction;
use Modules\Customer\Models\Customer;
use Modules\User\Models\User;
use Spatie\Multitenancy\Models\Tenant;

final class CampaignTransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CampaignTransaction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        Brand::first()->makeCurrent();

        $customer = Customer::inRandomOrder()->first() ?? Customer::factory()->create();

        return [
            'affiliate_id' => User::first() ?? User::factory()->create(),
            'type' => $this->faker->randomElement(CampaignTransactionType::getValues()),
            'amount' => $this->faker->randomFloat(2, 50, 500),
            'customer_ids' => Arr::wrap($customer->id),
        ];
    }
}
