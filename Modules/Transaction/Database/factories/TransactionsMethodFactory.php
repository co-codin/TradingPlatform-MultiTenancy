<?php

declare(strict_types=1);

namespace Modules\Transaction\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Transaction\Models\TransactionsMethod;

final class TransactionsMethodFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TransactionsMethod::class;

    /**
     * {@inheritDoc}
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(2),
            'title' => $this->faker->title(),
            'is_active' => $this->faker->boolean(),
        ];
    }
}
