<?php

namespace Modules\Worker\Http\Requests\Brand;

use App\Http\Requests\BaseFormRequest;

class WorkerBrandUpdateRequest extends BaseFormRequest
{
    public function rules()
    {
        return [
            'brands' => [
                'required',
                'array',
            ],
            'brands.*.id' => 'distinct|integer|exists:brands,id',
        ];
    }
}
