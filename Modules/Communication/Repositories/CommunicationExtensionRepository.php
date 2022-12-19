<?php

declare(strict_types=1);

namespace Modules\Communication\Repositories;

use App\Repositories\BaseRepository;
use Modules\Communication\Models\CommunicationExtension;
use Modules\Communication\Repositories\Criteria\CommunicationExtensionRequestCriteria;
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
