<?php

declare(strict_types=1);

namespace Modules\Splitter\Repositories;

use App\Repositories\BaseRepository;
use Modules\Splitter\Models\Splitter;
use Modules\Splitter\Repositories\Criteria\SplitterRequestCriteria;

final class SplitterRepository extends BaseRepository
{
    /**
     * {@inheritDoc}
     */
    final public function model(): string
    {
        return Splitter::class;
    }

    /**
     * {@inheritDoc}
     */
    final public function boot()
    {
        $this->pushCriteria(SplitterRequestCriteria::class);
    }
}
