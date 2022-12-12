<?php

declare(strict_types=1);

namespace Modules\TelephonyProvider\Repositories;

use App\Repositories\BaseRepository;
use Modules\Sale\Repositories\Criteria\TelephonyProviderRequestCriteria;
use Modules\TelephonyProvider\Models\TelephonyProvider;
use Prettus\Repository\Exceptions\RepositoryException;

final class TelephonyProviderRepository extends BaseRepository
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return TelephonyProvider::class;
    }

    /**
     * {@inheritDoc}
     *
     * @throws RepositoryException
     */
    public function boot()
    {
        $this->pushCriteria(TelephonyProviderRequestCriteria::class);
    }
}
