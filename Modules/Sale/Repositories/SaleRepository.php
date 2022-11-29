<?php

declare(strict_types=1);

namespace Modules\Sale\Repositories;

use App\Repositories\BaseRepository;
use Modules\Sale\Models\SaleStatus;
use Modules\Sale\Repositories\Criteria\SaleRequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;

final class SaleRepository extends BaseRepository
{
    /**
     * @inheritDoc
     */
    public function model(): string
    {
        return SaleStatus::class;
    }

    /**
     * @inheritDoc
     * @throws RepositoryException
     */
    public function boot()
    {
        $this->pushCriteria(SaleRequestCriteria::class);
    }
}
