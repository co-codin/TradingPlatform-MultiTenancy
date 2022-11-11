<?php

namespace Modules\Geo\Repositories;

use App\Repositories\BaseRepository;
use Modules\Geo\Models\Country;

class CountryRepository extends BaseRepository
{
    /**
     * @inheritDoc
     */
    public function model(): string
    {
        return Country::class;
    }
}
