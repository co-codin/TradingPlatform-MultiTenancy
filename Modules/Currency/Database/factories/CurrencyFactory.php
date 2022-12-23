<?php

declare(strict_types=1);

namespace Modules\User\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Currency\Models\Currency;

final class CurrencyFactory extends Factory
{
    /**
     * {@inheritdoc}
     */
    protected $model = Currency::class;

    /**
     * {@inheritDoc}
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->name(),
            'iso3' => $this->faker->unique()->currencyCode(),
            'symbol' => $this->faker->name(),
            'is_available' => true,
        ];
    }
}
