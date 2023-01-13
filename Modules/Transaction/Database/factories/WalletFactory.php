<?php

declare(strict_types=1);

namespace Modules\Transaction\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Currency\Models\Currency;
use Modules\Customer\Models\Customer;
use Modules\Transaction\Models\Wallet;
use Spatie\Multitenancy\Landlord;
use Spatie\Multitenancy\Models\Tenant;

final class WalletFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Wallet::class;

    /**
     * {@inheritDoc}
     */
    public function definition(): array
    {
        $tenant = Tenant::current();
        $customer = Customer::inRandomOrder()->first();

        if (! $customer) {
            $customer = Landlord::execute(fn () => Customer::factory()->make());
            $tenant->makeCurrent();
            $customer->save();
            $tenant->makeCurrent();
        }

        return [
            'name' => $this->faker->sentence(2),
            'title' => $this->faker->title(),
            'mt5_id' => $this->faker->unique()->name(),
            'currency_id' => Currency::inRandomOrder()->first() ?? Currency::factory(),
            'customer_id' => $customer->id,
        ];
    }
}
