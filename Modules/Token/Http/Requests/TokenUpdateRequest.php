<?php

namespace Modules\Token\Http\Requests;

use App\Http\Requests\BaseFormRequest;

class TokenUpdateRequest extends BaseFormRequest
{
    public function rules()
    {
        return [
            'worker_id' => 'sometimes|required|int|exists:workers,id',
            'token' => 'sometimes|required|string',
            'description' => 'sometimes|nullable',
            'ip' => 'sometimes|required|string',
        ];
    }
}
