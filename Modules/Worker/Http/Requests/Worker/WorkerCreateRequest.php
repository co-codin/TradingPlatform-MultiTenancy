<?php


namespace Modules\Worker\Http\Requests\Worker;


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
        ];
    }

    public function attributes()
    {
        return [
            'role_id' => 'Role',
        ];
    }
}
