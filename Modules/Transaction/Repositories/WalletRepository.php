<?php

declare(strict_types=1);

namespace Modules\Transaction\Repositories;

use App\Repositories\BaseRepository;
use Modules\Transaction\Models\Wallet;
use Modules\Transaction\Repositories\Criteria\WalletRequestCriteria;

final class WalletRepository extends BaseRepository
{
    /**
     * {@inheritDoc}
     */
    final public function model(): string
    {
        return Wallet::class;
    }

    /**
     * {@inheritDoc}
     */
    final public function boot()
    {
        $this->pushCriteria(WalletRequestCriteria::class);
    }
}
