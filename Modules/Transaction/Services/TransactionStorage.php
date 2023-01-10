<?php

declare(strict_types=1);

namespace Modules\Transaction\Services;

use Exception;
use Modules\Transaction\Dto\TransactionDto;
use Modules\Transaction\Models\Transaction;

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
        if (! $transaction = Transaction::query()->create($transactionDto->toArray())) {
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
