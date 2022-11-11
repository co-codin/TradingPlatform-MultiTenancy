<?php

namespace Modules\Geo\Services;

use LogicException;
use Modules\Geo\Models\Country;

class CountryStorage
{
    /**
     * Store country.
     *
     * @param array $data
     * @return Country
     */
    public function store(array $data): Country
    {
        return Country::create($data);
    }

    /**
     * Update country.
     *
     * @param Country $country
     * @param array $data
     * @return Country
     * @throws LogicException
     */
    public function update(Country $country, array $data): Country
    {
        if (! $country->update($data)) {
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
