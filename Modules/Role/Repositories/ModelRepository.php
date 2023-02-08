<?php

declare(strict_types=1);

namespace Modules\Role\Repositories;

use App\Models\Model;
use App\Repositories\BaseRepository;
use Illuminate\Support\Collection;
use Modules\Role\Repositories\Criteria\ModelRequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;

final class ModelRepository extends BaseRepository
{
    /**
     * {@inheritDoc}
     *
     * @throws RepositoryException
     */
    public function boot(): void
    {
        $this->pushCriteria(ModelRequestCriteria::class);
    }

    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return Model::class;
    }

    public function allWithoutNamespaces(): Collection
    {
        return $this->all()
            ->map(function (Model $model) {
                $explode = explode('\\', $model->name);
                $model->name = end($explode);

                return $model;
            })
            ->when(
                isset($_GET['sort']),
                function ($collection) {
                    if ($_GET['sort'] === 'name') {
                        return $collection->sortBy('name');
                    }

                    if ($_GET['sort'] === '-name') {
                        return $collection->sortByDesc('name');
                    }
                },
            );
    }
}
