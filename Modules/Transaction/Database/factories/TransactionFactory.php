<?php

declare(strict_types=1);

namespace Modules\Transaction\Database\factories;

use Database\Factories\BaseFactory;
use Modules\Customer\Models\Customer;
use Modules\Department\Enums\DepartmentEnum;
use Modules\Transaction\Enums\TransactionMt5TypeEnum;
use Modules\Transaction\Enums\TransactionType;
use Modules\Transaction\Models\Transaction;
use Modules\Transaction\Models\TransactionsMethod;
use Modules\Transaction\Models\TransactionsMt5Type;
use Modules\Transaction\Models\TransactionStatus;
use Modules\Transaction\Models\Wallet;
use Modules\User\Models\User;
use Spatie\Multitenancy\Models\Tenant;

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
        $fromCustomer = $this->faker->boolean;
        $type = $this->faker->randomElement(TransactionType::getValues());
        $mt5Type = $this->faker->randomElement($type === TransactionType::WITHDRAWAL
            ? [TransactionMt5TypeEnum::BALANCE, TransactionMt5TypeEnum::CREDIT]
            : TransactionMt5TypeEnum::getValues());

        /** @var Customer $customer */
        $customer = Customer::inRandomOrder()->first() ?? Customer::factory()->create();
        $wallet = Wallet::inRandomOrder()->first() ?? Wallet::factory()->create();

        return [
            'type' => $type,
            'mt5_type_id' => TransactionsMt5Type::firstWhere('name', $mt5Type)->id,
            'status_id' => TransactionStatus::inRandomOrder()->first()->id,
            'creator_id' => $fromCustomer ? $customer->id : User::inRandomOrder()->first()->id,
            'customer_id' => $customer->id,
            'worker_id' => match ($customer->department->name) {
                DepartmentEnum::CONVERSION => $customer->conversion_user_id,
                DepartmentEnum::RETENTION => $customer->retention_user_id,
                default => null,
            },
            'amount' => $this->faker->randomFloat(nbMaxDecimals: 10, max: 20),
            'method_id' => TransactionsMethod::inRandomOrder()->first()->id,
            'wallet_id' => $wallet->id,
            'currency_id' => $wallet->currency->id,
            'external_id' => (string) ($this->faker->boolean ? $this->faker->uuid : $this->faker->numberBetween()),
            'description' => $this->faker->sentence,
        ];
    }
}
