<?php

declare(strict_types=1);

namespace Modules\User\Http\Requests;

use App\Http\Requests\BaseFormRequest;

final class ResetPasswordRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
            'token' => 'required',
        ];
    }

    public function attributes(): array
    {
        return [
            'email' => 'Email',
            'password' => 'Password',
            'token' => 'Token',
        ];
    }

    public function validated($key = null, $default = null)
    {
        if ($key === 'email') {
            return $this->input('email', $default);
        }

        if ($key) {
            return parent::validated($key, $default);
        }

        return array_merge(parent::validated(), ['email' => $this->input('email')]);
    }

    final protected function passedValidation(): void
    {
        parent::passedValidation();
        $this->merge([
            'email' => strtolower($this->input('email')),
        ]);
    }
}
