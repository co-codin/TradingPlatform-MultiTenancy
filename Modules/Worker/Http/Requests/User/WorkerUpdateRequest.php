<?php


namespace Modules\Worker\Http\Requests\User;


use App\Http\Requests\BaseFormRequest;

class WorkerUpdateRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'role_id' => [
                'sometimes',
                'required',
                'array',
                'min:1',
                'exists:roles,id',
            ],
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'sometimes',
                'required',
                'email',
                'max:255',
                'unique:workers,email,' . $this->route('user'),
            ],
            'password' => [
                'exclude_unless:change_password,true',
                'required',
                'string',
                'confirmed',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'role_id' => 'Role',
        ];
    }
}
