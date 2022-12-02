<?php

declare(strict_types=1);

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class LoginRequest extends FormRequest
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

    protected function prepareForValidation()
    {
        $this->merge([
            'login' => strtolower($this->login),
        ]);
    }
}
