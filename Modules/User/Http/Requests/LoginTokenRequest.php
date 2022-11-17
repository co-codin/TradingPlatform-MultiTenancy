<?php

declare(strict_types=1);

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class LoginTokenRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string',
            'remember_me' => 'boolean',
        ];
    }
}
