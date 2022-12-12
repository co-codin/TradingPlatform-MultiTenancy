<?php

declare(strict_types=1);

namespace Modules\CommunicationProvider\Repositories;

use App\Repositories\BaseRepository;
use Modules\Sale\Repositories\Criteria\CommunicationProviderRequestCriteria;
use Modules\CommunicationProvider\Models\CommunicationProvider;
use Prettus\Repository\Exceptions\RepositoryException;

final class CommunicationProviderRepository extends BaseRepository
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return CommunicationProvider::class;
    }

    /**
     * {@inheritDoc}
     *
     * @throws RepositoryException
     */
    public function boot()
    {
        $this->pushCriteria(CommunicationProviderRequestCriteria::class);
    }
}
