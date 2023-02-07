<?php

namespace App\Services\Validation;

use Exception;
use Modules\Geo\Models\Country;
use Propaganistas\LaravelPhone\Rules\Phone as BasePhone;

class Phone extends BasePhone
{
    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    public function country($country): Phone
    {
        return parent::country(match (true) {
            $country instanceof Country => [$country->iso2],
            is_int($country) => [Country::query()->whereIsForbidden(false)->findOrFail($country)->iso2],
            is_array($country) => $country,
            is_string($country) => func_get_args(),
            default => throw new Exception(),
        });
    }
}
