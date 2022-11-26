<?php

namespace Modules\Config\Http\Requests;

use App\Http\Requests\BaseFormRequest;

class ConfigCreateRequest extends BaseFormRequest
{
    public function rules()
    {
        return [
            'config_type_id' => 'required|integer|exists:config_types,id',
            'data_type' => '',
            'name' => '',
            'value' => ''
        ];
    }
}
