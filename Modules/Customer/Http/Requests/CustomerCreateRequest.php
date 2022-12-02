<?php

namespace Modules\Customer\Http\Requests;

use App\Http\Requests\BaseFormRequest;

class CustomerCreateRequest extends BaseFormRequest
{
/**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'title' => 'required|string',
            'color' => 'required|string',
        ];
    }
}
