<?php

declare(strict_types=1);

namespace Modules\Transaction\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Transaction\Models\TransactionsWallet;
use Modules\Currency\Models\Currency;

final class TransactionsWalletFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TransactionsWallet::class;

    /**
     * {@inheritDoc}
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(2),
            'title' => $this->faker->title(),
            'mt5_id' => $this->faker->unique()->name(),
            'currency_id' => Currency::factory(),
        ];
    }
}
