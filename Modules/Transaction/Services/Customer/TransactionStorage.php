<?php

declare(strict_types=1);

namespace Modules\Transaction\Services\Customer;

use Exception;
use Modules\Config\Enums\ConfigDataTypeEnum;
use Modules\Config\Enums\ConfigEnum;
use Modules\Config\Enums\ConfigTypeEnum;
use Modules\Config\Models\Config;
use Modules\Role\Enums\ModelHasPermissionStatus;
use Modules\Transaction\Dto\TransactionDto;
use Modules\Transaction\Enums\TransactionMt5TypeEnum;
use Modules\Transaction\Enums\TransactionPermission;
use Modules\Transaction\Enums\TransactionStatusEnum;
use Modules\Transaction\Enums\TransactionType;
use Modules\Transaction\Models\Transaction;
use Modules\Transaction\Models\TransactionStatus;

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
            TransactionStatus::query()->where('name', TransactionStatusEnum::PENDING)->firstOrFail()
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

        if ($transaction->wallet->customer->id !== auth()->id()) {
            throw new Exception(__('It is not your wallet'));
        }

        // Checking for withdrawal
        if ($transaction->isWithdrawal() && $transaction->isBalanceMt5Type()) {
            $customerTransactionConfig = Config::whereHas('configType', fn ($q) => $q->where('name', ConfigTypeEnum::TRANSACTION))
                ->where('data_type', ConfigDataTypeEnum::JSON)
                ->where('name', ConfigEnum::CUSTOMER_RESTRICTIONS)
                ->first();

            if ($customerTransactionConfig) {
                // Check min withdrawal amount by current currency
                if (isset($customerTransactionConfig->value[$transaction->wallet?->currency?->iso3]['min_withdraw'])) {
                    if (
                        $transaction->amount
                        < $customerTransactionConfig->value[$transaction->wallet?->currency?->iso3]['min_withdraw']
                    ) {
                        throw new Exception(__('Amount less, than minimum withdrawal amount'));
                    }
                // Check min withdrawal amount by USD currency
                } elseif (isset($customerTransactionConfig->value['USD']['min_withdraw'])) {
                    if ($transaction->amount_usd < $customerTransactionConfig->value['USD']['min_withdraw']) {
                        throw new Exception(__('Amount less, than minimum withdrawal amount'));
                    }
                // Check min withdrawal amount by EUR currency
                } elseif (isset($customerTransactionConfig->value['EUR']['min_withdraw'])) {
                    if ($transaction->amount_eur < $customerTransactionConfig->value['EUR']['min_withdraw']) {
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
                    ->whereHas('status', fn ($q) => $q->where('name', TransactionStatusEnum::PENDING))
                    ->whereHas('mt5Type', fn ($q) => $q->where('name', TransactionMt5TypeEnum::BALANCE))
                    ->exists()
            ) {
                throw new Exception(__('Withdrawal transaction already created'));
            }
        }

        if (! $transaction->save()) {
            throw new Exception(__('Can not store transaction'));
        }

        return $transaction;
    }
}
