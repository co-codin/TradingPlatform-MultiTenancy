<?php

namespace Modules\Geo\Services;

use LogicException;
use Modules\Geo\Dto\CountryDto;
use Modules\Geo\Models\Country;

class CountryStorage
{
    /**
     * Store country.
     *
     * @param CountryDto $dto
     * @return Country
     */
    public function store(CountryDto $dto): Country
    {
        $country = Country::create($dto->toArray());

        if (! $country) {
            throw new LogicException(__('Can not create country'));
        }

        return $country;
    }

    /**
     * Update country.
     *
     * @param Country $country
     * @param CountryDto $dto
     * @return Country
     * @throws LogicException
     */
    public function update(Country $country, CountryDto $dto): Country
    {
        if (! $country->update($dto->toArray())) {
            throw new LogicException(__('Can not update country'));
        }

        return $country;
    }

    /**
     * Delete country.
     *
     * @param Country $country
     * @return bool
     */
    public function delete(Country $country): bool
    {
        if (! $country->delete()) {
            throw new LogicException(__('Can not delete country'));
        }

        return true;
    }
}
