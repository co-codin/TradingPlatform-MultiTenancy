<?php

declare(strict_types=1);

namespace Modules\Transaction\Repositories;

use App\Repositories\BaseRepository;
use Modules\Transaction\Models\TransactionStatus;
use Modules\Transaction\Repositories\Criteria\TransactionStatusRequestCriteria;

final class TransactionStatusRepository extends BaseRepository
{
    /**
     * {@inheritDoc}
     */
    final public function model(): string
    {
        return TransactionStatus::class;
    }

    /**
     * {@inheritDoc}
     */
    final public function boot()
    {
        $this->pushCriteria(TransactionStatusRequestCriteria::class);
    }
}
