<?php

namespace Modules\Customer\Rules;

use Illuminate\Contracts\Validation\Rule;
use Modules\Customer\Enums\ForbiddenCountry;
use Modules\Geo\Models\Country;

class ForbiddenCountryRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return !ForbiddenCountry::hasValue(Country::find($value)?->iso2);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Forbidden Country.';
    }
}
