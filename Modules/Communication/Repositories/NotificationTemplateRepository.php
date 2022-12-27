<?php

declare(strict_types=1);

namespace Modules\Communication\Repositories;

use App\Repositories\BaseRepository;
use Modules\Communication\Models\NotificationTemplate;
use Modules\Communication\Repositories\Criteria\NotificationTemplateRequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;

final class NotificationTemplateRepository extends BaseRepository
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return NotificationTemplate::class;
    }

    /**
     * {@inheritDoc}
     *
     * @throws RepositoryException
     */
    public function boot()
    {
        $this->pushCriteria(NotificationTemplateRequestCriteria::class);
    }
}
