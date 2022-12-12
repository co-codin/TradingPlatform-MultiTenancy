<?php

declare(strict_types=1);

namespace Modules\Sale\Repositories;

use App\Repositories\BaseRepository;
use Modules\CommunicationProvider\Models\CommunicationExtension;
use Modules\Sale\Repositories\Criteria\CommunicationExtensionRequestCriteria;
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
