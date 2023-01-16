<?php

declare(strict_types=1);

namespace Modules\User\Http\Requests;

use App\Http\Requests\BaseFormRequest;

final class UserCreateRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'username' => [
                'required',
                'string',
                'max:255',
                'unique:landlord.users,username',
                'regex:/^[\p{Alphabetic}0-9]+(?:_[\p{Alphabetic}0-9]+)*$/u',
            ],
            'first_name' => [
                'required',
                'string',
                'max:255',
            ],
            'last_name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'email',
                'max:255',
                'unique:landlord.users,email',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z])[\p{Alphabetic}0-9!@#$%^&*]{8,}/',
            ],
            'is_active' => 'boolean',
            'target' => 'numeric',
            'parent_id' => 'integer|exists:landlord.users,id',
            'roles' => 'required|array',
            'roles.*.id' => [
                'required',
                'integer',
                'min:1',
                'exists:landlord.roles,id',
            ],
            'desks' => 'sometimes|required|array',
            'desks.*.id' => [
                'required',
                'integer',
                'min:1',
                'exists:tenant.desks,id',
            ],
            'languages' => 'sometimes|required|array',
            'languages.*.id' => [
                'required',
                'integer',
                'min:1',
                'exists:tenant.languages,id',
            ],
            'countries' => 'sometimes|required|array',
            'countries.*.id' => [
                'required',
                'integer',
                'min:1',
                'exists:tenant.countries,id',
            ],
            'affiliate_id' => 'nullable|integer|exists:landlord.users,id',
            'show_on_scoreboards' => 'sometimes|required|boolean',
            'communication_provider_id' => 'nullable|integer|exists:tenant.communication_providers,id',
        ];
    }

    public function attributes(): array
    {
        return [
            'role_id' => 'Role',
        ];
    }
}
