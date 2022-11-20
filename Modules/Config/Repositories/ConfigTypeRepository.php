<?php

namespace Modules\Config\Repositories;

use App\Repositories\BaseRepository;
use Modules\Config\Models\Config;
use Modules\Config\Repositories\Criteria\ConfigTypeRequestCriteria;

class ConfigTypeRepository extends BaseRepository
{
    public function model()
    {
        return Config::class;
    }

    public function boot()
    {
        $this->pushCriteria(ConfigTypeRequestCriteria::class);
    }
}
