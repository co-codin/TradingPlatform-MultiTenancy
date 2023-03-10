<?php

namespace Modules\Department\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => "sometimes|required|string|max:35|unique:tenant.departments,name,{$this->route('department')}",
            'title' => "sometimes|required|string|max:35|unique:tenant.departments,title,{$this->route('department')}",
            'is_active' => 'sometimes|required|boolean',
            'is_default' => 'sometimes|required|boolean',
        ];
    }
}
