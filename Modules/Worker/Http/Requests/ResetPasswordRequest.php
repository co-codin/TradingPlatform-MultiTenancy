<?php
declare(strict_types=1);

namespace Modules\Worker\Http\Requests;

use App\Http\Requests\BaseFormRequest;

class ResetPasswordRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:workers,email',
            'password' => 'required|min:8|confirmed',
        ];
    }

    public function attributes(): array
    {
        return [
            'email' => 'Email',
            'password' => 'Password',
        ];
    }
}
