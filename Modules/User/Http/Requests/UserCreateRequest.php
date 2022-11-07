<?php


<<<<<<<< HEAD:Modules/User/Http/Requests/UserCreateRequest.php
namespace Modules\User\Http\Requests;
========
namespace Modules\Worker\Http\Requests\Worker;
>>>>>>>> staging:Modules/Worker/Http/Requests/Worker/WorkerCreateRequest.php


use App\Http\Requests\BaseFormRequest;

class UserCreateRequest extends BaseFormRequest
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
                'unique:users,email',
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
            'role_id' => 'Роль',
        ];
    }
}
