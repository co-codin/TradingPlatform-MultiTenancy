<?php

namespace Modules\Geo\Repositories;

use App\Repositories\BaseRepository;
use Modules\Geo\Models\Country;
use Modules\Geo\Repositories\Criteria\CountryRequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;

class CountryRepository extends BaseRepository
{
    /**
     * @inheritDoc
     */
    public function model(): string
    {
        return Country::class;
    }

    /**
     * @inheritDoc
     * @throws RepositoryException
     */
    public function boot()
    {
        $this->pushCriteria(CountryRequestCriteria::class);
    }
}