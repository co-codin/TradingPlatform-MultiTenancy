<?php

declare(strict_types=1);

namespace Modules\Communication\Repositories;

use App\Repositories\BaseRepository;
use Modules\Communication\Models\CommunicationProvider;
use Modules\Communication\Repositories\Criteria\CommunicationProviderRequestCriteria;
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
