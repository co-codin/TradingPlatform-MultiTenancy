<?php

namespace Modules\Brand\Http\Requests;

use App\Http\Requests\BaseFormRequest;

class BrandCreateRequest extends BaseFormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'slug' => 'required|string|regex:/^[a-z0-9_\-]*$/|unique:brands,slug',
            'logo_url' => 'required|string|max:255',
            'is_active' => 'sometimes|boolean'
        ];
    }
}
