<?php

namespace Modules\Role\Http\Requests;

use App\Http\Requests\BaseFormRequest;

class RoleCreateRequest extends BaseFormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'key' => 'required|string|max:255|regex:/[A-Z0-9_-]+/',
            'guard_name' => 'sometimes|nullable|string|max:255',
            'permissions' => 'sometimes|required|array',
            'permissions.*.id' => 'required|exists:permissions,id',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Role name',
            'description' => 'Role Description',
            'guard_name' => 'guard_name',
        ];
    }
}
