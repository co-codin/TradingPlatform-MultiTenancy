<?php

namespace Modules\Worker\Http\Requests\Desk;

use App\Http\Requests\BaseFormRequest;

class WorkerDeskUpdateRequest extends BaseFormRequest
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
