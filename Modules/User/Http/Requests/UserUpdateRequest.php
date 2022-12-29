<?php

declare(strict_types=1);

namespace Modules\User\Http\Requests;

use App\Http\Requests\BaseFormRequest;

final class UserUpdateRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'username' => [
                'sometimes',
                'required',
                'string',
                'max:255',
            ],
            'first_name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
            ],
            'last_name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'email',
                'max:255',
                'unique:landlord.users,email,' . $this->route('worker'),
            ],
            'is_active' => 'boolean',
            'parent_id' => 'integer|exists:landlord.users,id',
            'change_password' => [
                'nullable',
                'boolean',
            ],
            'password' => [
                'exclude_unless:change_password,true',
                'required',
                'string',
            ],
            'roles.*.id' => [
                'sometimes',
                'required',
                'integer',
                'min:1',
                'exists:landlord.roles,id',
            ],
            'affiliate_id' => 'nullable|integer|exists:landlord.users,id',
            'show_on_scoreboards' => 'sometimes|required|boolean',
            'communication_provider_id' => 'nullable|integer|exists:tenant.communication_providers,id',
        ];
    }

    public function attributes(): array
    {
        return [
            'role_id' => 'Роль',
        ];
    }
}
