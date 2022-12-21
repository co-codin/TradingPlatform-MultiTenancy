<?php

namespace Modules\Communication\Repositories;

use App\Repositories\BaseRepository;
use Modules\Communication\Models\Call;
use Modules\Communication\Repositories\Criteria\CallRequestCriteria;

class CallRepository extends BaseRepository
{
    public function model()
    {
        return Call::class;
    }

    public function boot()
    {
        $this->pushCriteria(CallRequestCriteria::class);
    }
}
