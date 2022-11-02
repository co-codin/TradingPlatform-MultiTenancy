<?php

namespace Modules\Desk\Http\Requests;

use App\Http\Requests\BaseFormRequest;

class DeskCreateRequest extends BaseFormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'is_active' => 'sometimes|boolean',
            'parent_id' => 'sometimes|int|exists:desks,id',
        ];
    }
}
