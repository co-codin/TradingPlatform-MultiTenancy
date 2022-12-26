<?php

declare(strict_types=1);

namespace Modules\Transaction\Repositories;

use App\Repositories\BaseRepository;
use Modules\Transaction\Models\TransactionsMt5Type;
use Modules\Transaction\Repositories\Criteria\TransactionsMt5TypeRequestCriteria;

final class TransactionsMt5TypeRepository extends BaseRepository
{
    /**
     * {@inheritDoc}
     */
    final public function model(): string
    {
        return TransactionsMt5Type::class;
    }

    /**
     * {@inheritDoc}
     */
    final public function boot()
    {
        $this->pushCriteria(TransactionsMt5TypeRequestCriteria::class);
    }
}
