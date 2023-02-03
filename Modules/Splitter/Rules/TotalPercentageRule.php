<?php

declare(strict_types=1);

namespace Modules\Splitter\Rules;

use Illuminate\Contracts\Validation\Rule;

final class TotalPercentageRule implements Rule
{
    public function __construct(
        protected bool $isPercent
    ) {
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $this->isPercent ? ((collect($value)->sum('percentage_per_day') == 100) ?? false) : true;
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
