<?php

declare(strict_types=1);

namespace Modules\ActivityLog\Repositories;

use App\Repositories\BaseRepository;
use Modules\ActivityLog\Models\ActivityLog;
use Modules\ActivityLog\Repositories\Criteria\ActivityLogRequestCriteria;

final class ActivityLogRepository extends BaseRepository
{
    /**
     * {@inheritDoc}
     */
    final public function model(): string
    {
        return ActivityLog::class;
    }

    /**
     * {@inheritDoc}
     */
    final public function boot()
    {
        $this->pushCriteria(ActivityLogRequestCriteria::class);
    }
}
