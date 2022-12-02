<?php

namespace Modules\Desk\Repositories;

use App\Repositories\BaseRepository;
use Modules\Desk\Models\Desk;
use Modules\Desk\Repositories\Criteria\DeskRequestCriteria;

class DeskRepository extends BaseRepository
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return Desk::class;
    }

    /**
     * {@inheritDoc}
     */
    public function boot()
    {
        $this->pushPermissionColumnValidator(DeskColumnPermissionValidator::class);
        $this->pushCriteria(DeskRequestCriteria::class);
    }
}
