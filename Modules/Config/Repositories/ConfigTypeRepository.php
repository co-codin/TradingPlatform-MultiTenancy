<?php

declare(strict_types=1);

namespace Modules\Config\Repositories;

use App\Repositories\BaseRepository;
use Modules\Config\Models\Config;
use Modules\Config\Repositories\Criteria\ConfigTypeRequestCriteria;

final class ConfigTypeRepository extends BaseRepository
{
    /**
     * {@inheritDoc}
     */
    final public function model(): string
    {
        return Config::class;
    }

    /**
     * {@inheritDoc}
     */
    final public function boot()
    {
        $this->pushCriteria(ConfigTypeRequestCriteria::class);
    }
}
