<?php


namespace Modules\Worker\Http\Requests\User;


use App\Http\Requests\BaseFormRequest;

class WorkerCreateRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'role_id' => [
                'required',
                'array',
                'min:1',
                'exists:roles,id',
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:workers,email',
            ],
            'password' => [
                'required',
                'string',
                'confirmed',
            ],
            'parent_id' => 'sometimes|int|exists:workers,id',
        ];
    }

    public function attributes()
    {
        return [
            'role_id' => 'Role',
        ];
    }
}
