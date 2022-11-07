<?php

namespace Modules\Token\Http\Requests;

use App\Http\Requests\BaseFormRequest;

class TokenUpdateRequest extends BaseFormRequest
{
    public function rules()
    {
        return [
            'user_id' => 'sometimes|required|int|exists:users,id',
            'token' => 'sometimes|required|string',
            'description' => 'sometimes|nullable',
            'ip' => 'sometimes|required|string',
        ];
    }
}
