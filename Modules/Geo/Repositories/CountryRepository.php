<?php

declare(strict_types=1);

namespace Modules\Geo\Repositories;

use App\Repositories\BaseRepository;
use Modules\Geo\Models\Country;
use Modules\Geo\Repositories\Criteria\CountryRequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;

final class CountryRepository extends BaseRepository
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return Country::class;
    }

    /**
     * {@inheritDoc}
     *
     * @throws RepositoryException
     */
    public function boot()
    {
        $this->pushPermissionColumnValidator(CountryColumnPermissionValidator::class);
        $this->pushCriteria(CountryRequestCriteria::class);
    }
}
