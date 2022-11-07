<?php

namespace Modules\Brand\Http\Requests;

use App\Http\Requests\BaseFormRequest;

class BrandUpdateRequest extends BaseFormRequest
{
    public function rules()
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'title' => 'sometimes|required|string|max:255',
            'slug' => 'sometimes|required|string|regex:/^[a-z0-9_\-]*$/|unique:brands,slug',
            'logo_url' => 'sometimes|required|string|max:255',
            'is_active' => 'sometimes|boolean'
        ];
    }
}
