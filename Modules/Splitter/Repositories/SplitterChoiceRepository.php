<?php

declare(strict_types=1);

namespace Modules\Splitter\Repositories;

use App\Repositories\BaseRepository;
use Modules\Splitter\Models\SplitterChoice;
use Modules\Splitter\Repositories\Criteria\SplitterChoiceRequestCriteria;

final class SplitterChoiceRepository extends BaseRepository
{
    /**
     * {@inheritDoc}
     */
    final public function model(): string
    {
        return SplitterChoice::class;
    }

    /**
     * {@inheritDoc}
     */
    final public function boot()
    {
        $this->pushCriteria(SplitterChoiceRequestCriteria::class);
    }
}
