<?php

namespace Modules\Token\Http\Requests;

use App\Http\Requests\BaseFormRequest;

class TokenCreateRequest extends BaseFormRequest
{
    public function rules()
    {
        return [
            'user_id' => 'required|int|exists:users,id',
            'token' => 'required|string',
            'description' => 'sometimes|nullable',
            'ip' => 'required|string',
        ];
    }
}
