<?php

declare(strict_types=1);

namespace Modules\Transaction\Database\factories;

use Database\Factories\BaseFactory;
use Modules\Transaction\Models\Transaction;

final class TransactionFactory extends BaseFactory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Transaction::class;

    /**
     * {@inheritDoc}
     */
    public function definition(): array
    {
        return [
            //
        ];
    }
}
