<?php

namespace Modules\Role\Repositories;

use App\Models\Action;
use App\Repositories\BaseRepository;
use Modules\Role\Repositories\Criteria\ActionRequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;

class ActionRepository extends BaseRepository
{
    /**
     * {@inheritDoc}
     *
     * @throws RepositoryException
     */
    public function boot(): void
    {
        $this->pushCriteria(ActionRequestCriteria::class);
    }

    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return Action::class;
    }
}
