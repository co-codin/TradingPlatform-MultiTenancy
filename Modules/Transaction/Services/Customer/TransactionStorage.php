<?php

declare(strict_types=1);

namespace Modules\Transaction\Services\Customer;

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
        $transaction = new Transaction($transactionDto->toArray());

        if (
            ! $transaction->is_ftd
            && $transaction->isDeposit()
            && $transaction->isApprovedStatus()
            && $transaction->isBalanceMt5Type()
            && $transaction->customer->transactions()->count() === 0
        ) {
            $transaction->is_ftd = true;
        }

        if ($transaction->save()) {
            throw new Exception(__('Can not store transaction'));
        }

        return $transaction;
    }
}
