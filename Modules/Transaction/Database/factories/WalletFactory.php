<?php

declare(strict_types=1);

namespace Modules\Transaction\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Currency\Models\Currency;
use Modules\Transaction\Models\Wallet;

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
        return [
            'name' => $this->faker->sentence(2),
            'title' => $this->faker->title(),
            'mt5_id' => $this->faker->unique()->name(),
            'currency_id' => Currency::inRandomOrder()->first() ?? Currency::factory(),
        ];
    }
}
