<?php

declare(strict_types=1);

namespace Modules\Transaction\Services;

use Exception;
use Modules\Transaction\Models\TransactionStatus;
use Modules\Transaction\Dto\TransactionStatusDto;

final class TransactionStatusStorage
{
    /**
     * Store.
     *
     * @param TransactionStatusDto $transactionStatusDto
     * @return TransactionStatus
     * @throws Exception
     */
    public function store(TransactionStatusDto $transactionStatusDto): TransactionStatus
    {
        if (! $transactionStatus = TransactionStatus::query()->create($transactionStatusDto->toArray())) {
            throw new Exception(__('Can not store transaction status'));
        }

        return $transactionStatus;
    }

    /**
     * Update.
     *
     * @param TransactionStatus $transactionStatus
     * @param TransactionStatusDto $transactionStatusDto
     * @return TransactionStatus
     * @throws Exception
     */
    public function update(TransactionStatus $transactionStatus, TransactionStatusDto $transactionStatusDto): TransactionStatus
    {
        if (! $transactionStatus->update($transactionStatusDto->toArray())) {
            throw new Exception('Can not update transaction status');
        }
        return $transactionStatus;
    }

    /**
     * Destroy.
     *
     * @param TransactionStatus $transactionStatus
     * @return void
     * @throws Exception
     */
    public function destroy(TransactionStatus $transactionStatus): void
    {
        if (! $transactionStatus->delete()) {
            throw new Exception('Can not delete transaction status');
        }
    }
}
