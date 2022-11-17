<?php

namespace Modules\User\Http\Requests\DisplayOption;

use App\Http\Requests\BaseFormRequest;

class UserDisplayOptionCreateRequest extends BaseFormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string',
            'columns' => 'nullable|array',
            'columns.*' => 'required|string'
        ];
    }
}
