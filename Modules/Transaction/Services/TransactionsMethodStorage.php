<?php

declare(strict_types=1);

namespace Modules\Transaction\Services;

use Exception;
use Modules\Transaction\Models\TransactionsMethod;
use Modules\Transaction\Dto\TransactionsMethodDto;

final class TransactionsMethodStorage
{
    /**
     * Store.
     *
     * @param TransactionsMethodDto $transactionsMethodDto
     * @return TransactionsMethod
     * @throws Exception
     */
    public function store(TransactionsMethodDto $transactionsMethodDto): TransactionsMethod
    {
        if (!$transactionsMethod = TransactionsMethod::query()->create($transactionsMethodDto->toArray())) {
            throw new Exception(__('Can not store transaction method'));
        }

        return $transactionsMethod;
    }

    /**
     * Update.
     *
     * @param TransactionsMethod $transactionsMethod
     * @param TransactionsMethodDto $transactionsMethodDto
     * @return TransactionsMethod
     * @throws Exception
     */
    public function update(TransactionsMethod $transactionsMethod, TransactionsMethodDto $transactionsMethodDto): TransactionsMethod
    {
        if (!$transactionsMethod->update($transactionsMethodDto->toArray())) {
            throw new Exception('Can not update transaction method');
        }
        return $transactionsMethod;
    }

    /**
     * Destroy.
     *
     * @param TransactionsMethod $transactionsMethod
     * @return void
     * @throws Exception
     */
    public function destroy(TransactionsMethod $transactionsMethod): void
    {
        if (!$transactionsMethod->delete()) {
            throw new Exception('Can not delete transaction method');
        }
    }
}
