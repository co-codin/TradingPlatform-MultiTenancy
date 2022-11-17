<?php

declare(strict_types=1);

namespace Modules\User\Repositories;

use App\Repositories\BaseRepository;
use Modules\User\Models\DisplayOption;
use Modules\User\Repositories\Criteria\DisplayOptionCriteria;

final class DisplayOptionRepository extends BaseRepository
{
    /**
     * {@inheritDoc}
     */
    final public function model(): string
    {
        return DisplayOption::class;
    }

    /**
     * {@inheritDoc}
     */
    final public function boot()
    {
        $this->pushCriteria(DisplayOptionCriteria::class);
    }
}
