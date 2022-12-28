<?php

declare(strict_types=1);

namespace Modules\Transaction\Repositories;

use App\Repositories\BaseRepository;
use Modules\Transaction\Models\TransactionsMethod;
use Modules\Transaction\Repositories\Criteria\TransactionsMethodRequestCriteria;

final class TransactionsMethodRepository extends BaseRepository
{
    /**
     * {@inheritDoc}
     */
    final public function model(): string
    {
        return TransactionsMethod::class;
    }

    /**
     * {@inheritDoc}
     */
    final public function boot()
    {
        $this->pushCriteria(TransactionsMethodRequestCriteria::class);
    }
}
