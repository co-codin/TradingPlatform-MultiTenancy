<?php
declare(strict_types=1);

namespace Modules\Worker\Http\Requests;

use App\Http\Requests\BaseFormRequest;

/**
 * @property string $email
 * @property string $password
 */
class AuthRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:workers,email',
            'password' => 'required',
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
