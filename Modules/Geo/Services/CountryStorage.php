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
        return Country::create($dto->toArray());
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
     * @param Country $brand
     * @return bool
     */
    public function delete(Country $brand): bool
    {
        if (! $brand->delete()) {
            throw new LogicException(__('Can not delete brand'));
        }

        return true;
    }
}
