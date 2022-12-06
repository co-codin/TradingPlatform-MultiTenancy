<?php

declare(strict_types=1);

namespace Modules\User\Http\Requests;

use App\Http\Requests\BaseFormRequest;

final class LoginRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'login' => 'required|string',
            'password' => 'required|string',
            'remember_me' => 'boolean',
        ];
    }

    public function validated($key = null, $default = null)
    {
        if ($key === 'login') {
            return $this->input('login', $default);
        }

        if ($key) {
            return parent::validated($key, $default);
        }

        return array_merge(parent::validated(), ['login' => $this->input('login')]);
    }

    final protected function passedValidation(): void
    {
        parent::passedValidation();
        $this->merge([
            'login' => strtolower($this->input('login')),
        ]);
    }
}
