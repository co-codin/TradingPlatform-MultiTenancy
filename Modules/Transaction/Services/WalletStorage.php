<?php

declare(strict_types=1);

namespace Modules\Transaction\Services;

use Exception;
use Modules\Transaction\Models\Wallet;
use Modules\Transaction\Dto\WalletDto;

final class WalletStorage
{
    /**
     * Store.
     *
     * @param WalletDto $transactionsWalletDto
     * @return Wallet
     * @throws Exception
     */
    public function store(WalletDto $transactionsWalletDto): Wallet
    {
        if (!$transactionsWallet = Wallet::query()->create($transactionsWalletDto->toArray())) {
            throw new Exception(__('Can not store wallet'));
        }

        return $transactionsWallet;
    }

    /**
     * Update.
     *
     * @param Wallet $transactionsWallet
     * @param WalletDto $transactionsWalletDto
     * @return Wallet
     * @throws Exception
     */
    public function update(Wallet $transactionsWallet, WalletDto $transactionsWalletDto): Wallet
    {
        if (!$transactionsWallet->update($transactionsWalletDto->toArray())) {
            throw new Exception('Can not update wallet');
        }
        return $transactionsWallet;
    }

    /**
     * Destroy.
     *
     * @param Wallet $transactionsWallet
     * @return void
     * @throws Exception
     */
    public function destroy(Wallet $transactionsWallet): void
    {
        if (!$transactionsWallet->delete()) {
            throw new Exception('Can not delete wallet');
        }
    }
}
