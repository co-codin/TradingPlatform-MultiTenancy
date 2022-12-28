<?php

declare(strict_types=1);

namespace Modules\Transaction\Services;

use Exception;
use Modules\Transaction\Models\Transaction;
use Modules\Transaction\Dto\TransactionDto;

final class TransactionStorage
{
    /**
     * Store.
     *
     * @param TransactionDto $transactionDto
     * @return Transaction
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
     * @param Transaction $transaction
     * @param TransactionDto $transactionDto
     * @return Transaction
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
     * Destroy.
     *
     * @param Transaction $transaction
     * @return void
     * @throws Exception
     */
    public function destroy(Transaction $transaction): void
    {
        if (! $transaction->delete()) {
            throw new Exception(__('Can not delete transaction'));
        }
    }
}
