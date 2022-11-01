<?php

namespace Modules\Desk\Http\Requests;

use App\Http\Requests\BaseFormRequest;

class DeskUpdateRequest extends BaseFormRequest
{
    public function rules()
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'title' => 'sometimes|required|string|max:255',
            'is_active' => 'sometimes|boolean',
            'parent_id' => 'sometimes|int|exists:desks,id',
        ];
    }
}
