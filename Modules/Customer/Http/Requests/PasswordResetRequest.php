<?php

declare(strict_types=1);

namespace Modules\Customer\Http\Requests;

use App\Enums\RegexValidationEnum;
use App\Http\Requests\BaseFormRequest;

final class PasswordResetRequest extends BaseFormRequest
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
                'confirmed',
                'regex:'.RegexValidationEnum::PASSWORD,
            ],
            'send_email' => 'sometimes|required|boolean',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function attributes(): array
    {
        return [
            'password' => 'Password',
            'send_email' => 'Send email',
        ];
    }
}
