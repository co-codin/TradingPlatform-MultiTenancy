<?php

declare(strict_types=1);

namespace Modules\Customer\Http\Requests;

use App\Http\Requests\BaseFormRequest;

final class PasswordResetRequest extends BaseFormRequest
{
    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
            'token' => 'required',
            'send_email' => 'sometimes|required|boolean',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function attributes(): array
    {
        return [
            'email' => 'Email',
            'password' => 'Password',
            'token' => 'Token',
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => strtolower($this->email),
        ]);
    }
}
