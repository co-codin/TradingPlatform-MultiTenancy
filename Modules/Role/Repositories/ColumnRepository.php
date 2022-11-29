<?php

declare(strict_types=1);

namespace Modules\Role\Repositories;

use App\Repositories\BaseRepository;
use Modules\Role\Models\Column;
use Modules\Role\Repositories\Criteria\ColumnRequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;

final class ColumnRepository extends BaseRepository
{
    /**
     * {@inheritDoc}
     *
     * @throws RepositoryException
     */
    public function boot(): void
    {
        $this->pushCriteria(ColumnRequestCriteria::class);
    }

    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return Column::class;
    }
}
