<?php

namespace Modules\Token\Http\Requests;

use App\Http\Requests\BaseFormRequest;

class TokenCreateRequest extends BaseFormRequest
{
    public function rules()
    {
        return [
            'worker_id' => 'required|int|exists:workers,id',
            'token' => 'required|string',
            'description' => 'sometimes|nullable',
            'ip' => 'required|string',
        ];
    }
}
