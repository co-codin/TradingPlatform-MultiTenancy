<?php

declare(strict_types=1);

namespace Modules\User\Repositories;

use App\Repositories\BaseRepository;
use Modules\User\Models\Preset;
use Modules\User\Repositories\Criteria\PresetCriteria;

final class PresetRepository extends BaseRepository
{
    /**
     * {@inheritDoc}
     */
    final public function model(): string
    {
        return Preset::class;
    }

    /**
     * {@inheritDoc}
     */
    final public function boot()
    {
        $this->pushCriteria(PresetCriteria::class);
    }
}
