<?php

declare(strict_types=1);

namespace Modules\User\Http\Requests\Department;

use App\Http\Requests\BaseFormRequest;

final class UserDepartmentUpdateRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'departments' => [
                'required',
                'array',
            ],
            'departments.*.id' => 'distinct|integer|exists:tenant.departments,id',
        ];
    }
}
