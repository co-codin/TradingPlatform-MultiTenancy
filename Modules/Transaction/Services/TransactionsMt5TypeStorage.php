<?php

declare(strict_types=1);

namespace Modules\Transaction\Services;

use Exception;
use Modules\Transaction\Models\TransactionsMt5Type;
use Modules\Transaction\Dto\TransactionsMt5TypeDto;

final class TransactionsMt5TypeStorage
{
    /**
     * Store.
     *
     * @param TransactionsMt5TypeDto $transactionsMt5TypeDto
     * @return TransactionsMt5Type
     * @throws Exception
     */
    public function store(TransactionsMt5TypeDto $transactionsMt5TypeDto): TransactionsMt5Type
    {
        if (!$transactionsMt5Type = TransactionsMt5Type::query()->create($transactionsMt5TypeDto->toArray())) {
            throw new Exception(__('Can not store transaction mt5 type'));
        }

        return $transactionsMt5Type;
    }

    /**
     * Update.
     *
     * @param TransactionsMt5Type $transactionsMt5Type
     * @param TransactionsMt5TypeDto $transactionsMt5TypeDto
     * @return TransactionsMt5Type
     * @throws Exception
     */
    public function update(TransactionsMt5Type $transactionsMt5Type, TransactionsMt5TypeDto $transactionsMt5TypeDto): TransactionsMt5Type
    {
        if (!$transactionsMt5Type->update($transactionsMt5TypeDto->toArray())) {
            throw new Exception('Can not update transaction mt5 type');
        }
        return $transactionsMt5Type;
    }

    /**
     * Destroy.
     *
     * @param TransactionsMt5Type $transactionsMt5Type
     * @return void
     * @throws Exception
     */
    public function destroy(TransactionsMt5Type $transactionsMt5Type): void
    {
        if (!$transactionsMt5Type->delete()) {
            throw new Exception('Can not delete transaction mt5 type');
        }
    }
}
