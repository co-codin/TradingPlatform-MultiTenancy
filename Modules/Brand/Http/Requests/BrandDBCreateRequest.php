<?php

namespace Modules\Brand\Http\Requests;

use App\Http\Requests\BaseFormRequest;

class BrandDBCreateRequest extends BaseFormRequest
{
    public function rules()
    {
        return [
            'tables' => [
                'required',
                'array',
            ]
        ];
    }

    protected function allowedTables()
    {
        return [

        ];
    }
}
