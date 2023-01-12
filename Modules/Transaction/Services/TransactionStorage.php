<?php

declare(strict_types=1);

namespace Modules\Transaction\Services;

use Exception;
use Modules\Customer\Models\Customer;
use Modules\Department\Enums\DepartmentEnum;
use Modules\Transaction\Dto\TransactionDto;
use Modules\Transaction\Enums\TransactionStatusEnum;
use Modules\Transaction\Models\Transaction;
use Modules\Transaction\Models\TransactionsMt5Type;
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
        $customer = Customer::find($transactionDto->customer_id);

        $workerId = match ($customer->department->name) {
            DepartmentEnum::CONVERSION => $customer->conversion_user_id,
            DepartmentEnum::RETENTION => $customer->retention_user_id,
            default => null
        };

        $transaction = Transaction::query()->create(array_merge($transactionDto->toArray(), [
            'mt5_type_id' => TransactionsMt5Type::firstWhere('name', $transactionDto->mt5_type)->id,
            'status_id' => TransactionStatus::firstWhere('name', $transactionDto->status)->id,
            'worker_id' => $workerId,
            'currency_id' => Wallet::find($transactionDto->wallet_id)->currency->id,
        ]));

        if (!$transaction) {
            throw new Exception(__('Can not store transaction'));
        }

        return $transaction;
    }

    /**
     * Update.
     *
     * @param  Transaction  $transaction
     * @param  TransactionDto  $transactionDto
     * @return Transaction
     *
     * @throws Exception
     */
    public function update(Transaction $transaction, TransactionDto $transactionDto): Transaction
    {
        if ($transaction->status_id != TransactionStatus::firstWhere('name', TransactionStatusEnum::PENDING)->id) {
            throw new Exception(__('Can not update transaction status'));
        }
        if (!$transaction->update(array_merge($transactionDto->toArray(), [
            'status_id' => TransactionStatus::firstWhere('name', $transactionDto->status)->id,
        ]))) {
            throw new Exception(__('Can not update transaction'));
        }

        return $transaction;
    }

    /**
     * Update Batch
     *
     * @param  mixed  $transaction
     * @param  mixed  $transactionArray
     * @return Transaction
     */
    public function updateBatch(Transaction $transaction, array $transactionArray): Transaction
    {
        if (!$transaction->update($transactionArray)) {
            throw new Exception(__('Can not update transaction'));
        }

        return $transaction;
    }
}
