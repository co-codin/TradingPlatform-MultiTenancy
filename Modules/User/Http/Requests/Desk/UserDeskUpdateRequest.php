<?php

namespace Modules\User\Http\Requests\Desk;

use App\Http\Requests\BaseFormRequest;

class UserDeskUpdateRequest extends BaseFormRequest
{
    public function rules()
    {
        return [
            'desks' => [
                'required',
                'array',
            ],
            'desks.*.id' => 'distinct|integer|exists:desks,id',
        ];
    }
}
