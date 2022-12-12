<?php

declare(strict_types=1);

namespace Modules\CommunicationProvider\Repositories;

use App\Repositories\BaseRepository;
use Modules\CommunicationProvider\Models\CommunicationExtension;
use Modules\CommunicationProvider\Repositories\Criteria\CommunicationExtensionRequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;

final class CommunicationExtensionRepository extends BaseRepository
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return CommunicationExtension::class;
    }

    /**
     * {@inheritDoc}
     *
     * @throws RepositoryException
     */
    public function boot()
    {
        $this->pushCriteria(CommunicationExtensionRequestCriteria::class);
    }
}
