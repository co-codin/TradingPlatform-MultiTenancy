<?php

declare(strict_types=1);

namespace Modules\Currency\Repositories;

use App\Repositories\BaseRepository;
use Modules\Currency\Models\Currency;
use Modules\Currency\Repositories\Criteria\CurrencyRequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;

final class CurrencyRepository extends BaseRepository
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return Currency::class;
    }

    /**
     * {@inheritDoc}
     *
     * @throws RepositoryException
     */
    public function boot()
    {
        $this->pushCriteria(CurrencyRequestCriteria::class);
    }
}
