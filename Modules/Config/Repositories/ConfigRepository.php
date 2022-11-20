<?php

namespace Modules\Config\Repositories;

use App\Repositories\BaseRepository;
use Modules\Config\Models\Config;
use Modules\Config\Repositories\Criteria\ConfigRequestCriteria;

class ConfigRepository extends BaseRepository
{
    public function model()
    {
        return Config::class;
    }

    public function boot()
    {
        $this->pushCriteria(ConfigRequestCriteria::class);
    }
}
