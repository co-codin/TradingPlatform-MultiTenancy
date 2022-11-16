<?php

namespace Modules\User\Http\Requests\DisplayOption;

use App\Http\Requests\BaseFormRequest;

class UserDisplayOptionUpdateRequest extends BaseFormRequest
{
    public function rules()
    {
        return [
            'name' => 'sometimes|required|string|unique:display_options,name',
            'columns' => 'nullable|array',
            'columns.*.' => 'required|string'
        ];
    }
}
