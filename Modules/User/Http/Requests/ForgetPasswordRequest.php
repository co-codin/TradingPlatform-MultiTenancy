<?php

declare(strict_types=1);

namespace Modules\User\Http\Requests;

use App\Http\Requests\BaseFormRequest;

final class ForgetPasswordRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|email',
        ];
    }

    public function attributes(): array
    {
        return [
            'email' => 'Email',
        ];
    }

    public function validated($key = null, $default = null)
    {
        if ($key === 'email') {
            return $this->input('email', $default);
        }

        return parent::validated($key, $default);
    }

    final protected function passedValidation(): void
    {
        parent::passedValidation();
        $this->merge([
            'email' => strtolower($this->input('email')),
        ]);
    }
}
