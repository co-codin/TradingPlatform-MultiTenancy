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
                'confirmed',
            ],
            'is_active' => 'boolean',
            'target' => 'numeric',
            'parent_id' => 'integer|exists:landlord.users,id',
            'roles.*.id' => [
                'required',
                'integer',
                'min:1',
                'exists:landlord.roles,id',
            ],
            'desks.*.id' => [
                'integer',
                'min:1',
                'exists:tenant.desks,id',
            ],
            'languages.*.id' => [
                'integer',
                'min:1',
                'exists:tenant.languages,id',
            ],
            'countries.*.id' => [
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
