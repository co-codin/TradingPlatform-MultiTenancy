<?php

declare(strict_types=1);

namespace Modules\Transaction\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Transaction\Enums\TransactionStatusName;
use Modules\Transaction\Models\TransactionStatus;

final class TransactionStatusFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TransactionStatus::class;

    /**
     * {@inheritDoc}
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(TransactionStatusName::asArray()),
            'title' => $this->faker->title(),
            'is_active' => $this->faker->boolean(),
            'is_valid' => $this->faker->boolean(),
        ];
    }
}
