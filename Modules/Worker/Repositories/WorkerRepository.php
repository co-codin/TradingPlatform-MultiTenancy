<?php

namespace Modules\Worker\Repositories;

use App\Repositories\BaseRepository;
use Modules\Worker\Models\Worker;
use Modules\Worker\Repositories\Criteria\WorkerRequestCriteria;

class WorkerRepository extends BaseRepository
{
    public function model()
    {
        return Worker::class;
    }

    public function boot()
    {
        $this->pushCriteria(WorkerRequestCriteria::class);
    }
}
