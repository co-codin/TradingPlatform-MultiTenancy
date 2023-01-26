<?php

declare(strict_types=1);

namespace Modules\Customer\Http\Requests;

use App\Enums\RegexValidationEnum;
use App\Http\Requests\BaseFormRequest;

final class PasswordChangeRequest extends BaseFormRequest
{
    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            'password' => [
                'required',
                'string',
                'regex:' . RegexValidationEnum::PASSWORD,
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function attributes(): array
    {
        return [
            'password' => 'Password',
        ];
    }
}
