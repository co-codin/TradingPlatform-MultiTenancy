<?php

namespace Modules\Department\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:departments,name',
            'title' => 'required|string|unique:departments,title',
            'is_active' => 'sometimes|required|boolean',
            'is_default' => 'sometimes|required|boolean',
        ];
    }
}
