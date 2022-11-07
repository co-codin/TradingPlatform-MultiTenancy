<?php
declare(strict_types=1);

namespace Modules\Worker\Http\Requests;

use App\Http\Requests\BaseFormRequest;

class ForgetPasswordRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:workers,email',
        ];
    }

    public function attributes(): array
    {
        return [
            'email' => 'Email',
        ];
    }
}
