<?php

declare(strict_types=1);

namespace Modules\Transaction\Services;

use Exception;
use Modules\Customer\Models\Customer;
use Modules\Department\Enums\DepartmentEnum;
use Modules\Transaction\Dto\TransactionDto;
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

        $transaction = Transaction::query()->create([
            'type' => $transactionDto->type,
            'mt5_type_id' => TransactionsMt5Type::where('name', $transactionDto->mt5_type)->first()->id,
            'status_id' => TransactionStatus::where('name', $transactionDto->status)->first()->id,
            'amount' => $transactionDto->amount,
            'customer_id' => $transactionDto->customer_id,
            'worker_id' => $workerId,
            'method_id' => $transactionDto->method_id,
            'wallet_id' => $transactionDto->wallet_id,
            'currency_id' => Wallet::find($transactionDto->wallet_id)->currency->id,
            'external_id' => $transactionDto->external_id,
            'description' => $transactionDto->description,
            'is_test' => $transactionDto->is_test,
        ]);

        if (! $transaction) {
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
        if (! $transaction->update($transactionDto->toArray())) {
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
        if (! $transaction->update($transactionArray)) {
            throw new Exception(__('Can not update transaction'));
        }

        return $transaction;
    }
}
