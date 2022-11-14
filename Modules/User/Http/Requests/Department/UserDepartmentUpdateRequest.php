<?php

namespace Modules\User\Http\Requests\Department;

use App\Http\Requests\BaseFormRequest;

class UserDepartmentUpdateRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'departments' => [
                'required',
                'array',
            ],
            'departments.*' => 'distinct|integer|exists:departments,id',
        ];
    }

    /**
     * Authorize.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }
}
