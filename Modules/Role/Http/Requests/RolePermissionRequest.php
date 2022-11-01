<?php


namespace Modules\Role\Http\Requests;


use App\Http\Requests\BaseFormRequest;

class RolePermissionRequest extends BaseFormRequest
{
    public function rules()
    {
        return [
            'permissions' => [
                'required',
                'array',
            ],
            'permissions.*.id' => [
                'required',
                'integer',
                'exists:permissions,id',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'permissions' => 'Permissions',
            'permissions.*.id' => 'Permission id',
        ];
    }
}
