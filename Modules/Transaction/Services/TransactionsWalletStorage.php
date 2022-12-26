<?php

declare(strict_types=1);

namespace Modules\Transaction\Services;

use Exception;
use Modules\Transaction\Models\TransactionsWallet;
use Modules\Transaction\Dto\TransactionsWalletDto;

final class TransactionsWalletStorage
{
    /**
     * Store.
     *
     * @param TransactionsWalletDto $transactionsWalletDto
     * @return TransactionsWallet
     * @throws Exception
     */
    public function store(TransactionsWalletDto $transactionsWalletDto): TransactionsWallet
    {
        if (!$transactionsWallet = TransactionsWallet::query()->create($transactionsWalletDto->toArray())) {
            throw new Exception(__('Can not store transaction wallet'));
        }

        return $transactionsWallet;
    }

    /**
     * Update.
     *
     * @param TransactionsWallet $transactionsWallet
     * @param TransactionsWalletDto $transactionsWalletDto
     * @return TransactionsWallet
     * @throws Exception
     */
    public function update(TransactionsWallet $transactionsWallet, TransactionsWalletDto $transactionsWalletDto): TransactionsWallet
    {
        if (!$transactionsWallet->update($transactionsWalletDto->toArray())) {
            throw new Exception('Can not update transaction wallet');
        }
        return $transactionsWallet;
    }

    /**
     * Destroy.
     *
     * @param TransactionsWallet $transactionsWallet
     * @return void
     * @throws Exception
     */
    public function destroy(TransactionsWallet $transactionsWallet): void
    {
        if (!$transactionsWallet->delete()) {
            throw new Exception('Can not delete transaction wallet');
        }
    }
}
