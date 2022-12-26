<?php

declare(strict_types=1);

namespace Modules\Transaction\Repositories;

use App\Repositories\BaseRepository;
use Modules\Transaction\Models\TransactionsWallet;
use Modules\Transaction\Repositories\Criteria\TransactionsWalletRequestCriteria;

final class TransactionsWalletRepository extends BaseRepository
{
    /**
     * {@inheritDoc}
     */
    final public function model(): string
    {
        return TransactionsWallet::class;
    }

    /**
     * {@inheritDoc}
     */
    final public function boot()
    {
        $this->pushCriteria(TransactionsWalletRequestCriteria::class);
    }
}
