<?php

declare(strict_types=1);

namespace Modules\Splitter\Rules;

use Illuminate\Contracts\Validation\Rule;

final class TotalPercentageRule implements Rule
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
        return (collect($value)->sum('percentage_per_day') == 100) ?? false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The total percentage per day must be 100%.';
    }
}
