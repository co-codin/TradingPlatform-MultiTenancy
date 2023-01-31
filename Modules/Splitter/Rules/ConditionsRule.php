<?php

namespace Modules\Splitter\Rules;

use Illuminate\Contracts\Validation\Rule;
use Modules\Splitter\Enums\SplitterConditions;

class ConditionsRule implements Rule
{
    private static string $message = 'The :attribute validation error.';

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $values
     * @return bool
     */
    public function passes($attribute, $values)
    {
        foreach ($values as $key => $value) {
            if (! isset($value['field'])) {
                self::$message = "The :attribute.{$key}.field is required.";

                return false;
            }

            if (! isset($value['operator'])) {
                self::$message = "The :attribute.{$key}.operator field is required.";

                return false;
            }

            if (! isset($value['value'])) {
                self::$message = "The :attribute.{$key}.value field is required.";

                return false;
            }

            $fields = SplitterConditions::fields();

            if (! isset($fields[trim($value['field'])])) {
                self::$message = "The :attribute.{$key}.field is invalid.";

                return false;
            }

            $fieldOperator = $fields[trim($value['field'])]['operator'];

            if (! in_array(trim($value['operator']), $fieldOperator)) {
                self::$message = "The :attribute.{$key}.operator is invalid. Valid operator: ".implode(', ', $fieldOperator);

                return false;
            }

            $fieldValueType = $fields[trim($value['field'])]['valueType'];

            if (! in_array(gettype($value['value']), $fieldValueType)) {
                self::$message = "The :attribute.{$key}.value type is invalid. Valid type: ".implode(', ', $fieldValueType);

                return false;
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return self::$message;
    }
}
