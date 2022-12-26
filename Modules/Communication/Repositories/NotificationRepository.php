<?php

declare(strict_types=1);

namespace Modules\Communication\Repositories;

use App\Repositories\BaseRepository;
use Modules\Communication\Models\DatabaseNotification;
use Modules\Communication\Repositories\Criteria\NotificationRequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;

final class NotificationRepository extends BaseRepository
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return DatabaseNotification::class;
    }

    /**
     * {@inheritDoc}
     *
     * @throws RepositoryException
     */
    public function boot()
    {
        $this->pushCriteria(NotificationRequestCriteria::class);
    }
}
