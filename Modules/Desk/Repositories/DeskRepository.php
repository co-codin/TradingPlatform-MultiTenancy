<?php

namespace Modules\Desk\Repositories;

use App\Repositories\BaseRepository;
use Modules\Desk\Models\Desk;
use Modules\Desk\Repositories\Criteria\DeskRequestCriteria;

class DeskRepository extends BaseRepository
{
    public function model()
    {
        return Desk::class;
    }

    public function boot()
    {
        $this->pushCriteria(DeskRequestCriteria::class);
    }
}
