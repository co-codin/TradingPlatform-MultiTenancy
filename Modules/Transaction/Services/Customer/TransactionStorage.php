<?php

declare(strict_types=1);

namespace Modules\Transaction\Services\Customer;

use Exception;
use Modules\Config\Enums\ConfigName;
use Modules\Config\Enums\ConfigType;
use Modules\Config\Enums\DataType;
use Modules\Config\Models\Config;
use Modules\Role\Enums\ModelHasPermissionStatus;
use Modules\Transaction\Dto\TransactionDto;
use Modules\Transaction\Enums\TransactionMt5TypeName;
use Modules\Transaction\Enums\TransactionPermission;
use Modules\Transaction\Enums\TransactionStatusName;
use Modules\Transaction\Enums\TransactionType;
use Modules\Transaction\Models\Transaction;
use Modules\Transaction\Models\TransactionStatus;
use Modules\Transaction\Models\Wallet;

final class TransactionStorage
{
    /**
     * Store.
     *
     * @param  TransactionDto  $transactionDto
     * @return Transaction
     *
     * @throws Exception
     */
    public function store(TransactionDto $transactionDto): Transaction
    {
        $transaction = new Transaction($transactionDto->toArray());

        // Preset pending status for transaction
        $transaction->status()->associate(
            TransactionStatus::query()->where('name', TransactionStatusName::PENDING)->firstOrFail()
        );

        // Is first deposit
        if (
            ! $transaction->is_ftd
            && $transaction->isDeposit()
            && $transaction->isApprovedStatus()
            && $transaction->isBalanceMt5Type()
            && $transaction->customer->transactions()->count() === 0
        ) {
            $transaction->is_ftd = true;
        }

        // Checking for withdrawal
        if ($transaction->isWithdrawal() && $transaction->isBalanceMt5Type()) {
            $customerTransactionConfig = Config::whereHas('configType', fn ($q) => $q->where('name', ConfigType::TRANSACTION))
                ->where('data_type', DataType::JSON)
                ->where('name', ConfigName::CUSTOMER_RESTRICTIONS)
                ->first();

            if ($customerTransactionConfig) {
                // Check min withdrawal amount by current currency
                if (isset($customerTransactionConfig->value['min_withdraw'][$transaction->currency->iso3])) {
                    if ($transaction->amount < $customerTransactionConfig->value['min_withdraw'][$transaction->currency->iso3]) {
                        throw new Exception(__('Amount less, than minimum withdrawal amount'));
                    }
                // Check min withdrawal amount by USD currency
                } elseif (isset($customerTransactionConfig->value['min_withdraw']['USD'])) {
                    if ($transaction->amount_usd < $customerTransactionConfig->value['min_withdraw']['USD']) {
                        throw new Exception(__('Amount less, than minimum withdrawal amount'));
                    }
                // Check min withdrawal amount by EUR currency
                } elseif (isset($customerTransactionConfig->value['min_withdraw']['EUR'])) {
                    if ($transaction->amount_eur < $customerTransactionConfig->value['min_withdraw']['EUR']) {
                        throw new Exception(__('Amount less, than minimum withdrawal amount'));
                    }
                } else {
                    throw new Exception(__('Change your currency to an available one'));
                }
            }

            // Transaction amount greater, then customer balance
            if ($transaction->amount > $transaction->customer->balance) {
                throw new Exception(__('Amount greater, than balance'));
            }

            // Transaction creating was suspended for this customer
            if (
                $transaction->customer->can(TransactionPermission::CREATE_TRANSACTIONS)
                && $transaction->customer
                    ->permissions()
                    ->where('name', TransactionPermission::CREATE_TRANSACTIONS)
                    ->first()
                    ->pivot
                    ->status === ModelHasPermissionStatus::SUSPENDED
            ) {
                throw new Exception(__('Transaction creation suspended'));
            }

            // Withdrawal transaction was already created
            if (
                $transaction->customer->transactions()
                    ->where('type', TransactionType::WITHDRAWAL)
                    ->whereHas('status', fn ($q) => $q->where('name', TransactionStatusName::PENDING))
                    ->whereHas('mt5Type', fn ($q) => $q->where('name', TransactionMt5TypeName::BALANCE))
                    ->exists()
            ) {
                throw new Exception(__('Withdrawal transaction already created'));
            }
        }

        if ($transaction->save()) {
            throw new Exception(__('Can not store transaction'));
        }

        return $transaction;
    }
}
