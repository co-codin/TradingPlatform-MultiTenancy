<?php

declare(strict_types=1);

namespace Modules\User\Http\Requests;

use App\Enums\RegexValidationEnum;
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
                "unique:landlord.users,username,{$this->route('worker')}",
                'regex:' . RegexValidationEnum::fromValue(RegexValidationEnum::USERNAME)->value,
            ],
            'first_name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                'regex:' . RegexValidationEnum::fromValue(RegexValidationEnum::FIRSTNAME)->value,
            ],
            'last_name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                'regex:' . RegexValidationEnum::fromValue(RegexValidationEnum::LASTNAME)->value,
            ],
            'email' => [
                'email',
                'max:255',
                "unique:landlord.users,email,{$this->route('worker')}",
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
                'regex:' . RegexValidationEnum::fromValue(RegexValidationEnum::PASSWORD)->value,
            ],
            'roles' => 'sometimes|required|array',
            'roles.*.id' => [
                'required',
                'distinct',
                'integer',
                'min:1',
                'exists:landlord.roles,id',
            ],
            'affiliate_id' => 'nullable|integer|exists:landlord.users,id',
            'show_on_scoreboards' => 'sometimes|required|boolean',
            'communication_provider_id' => 'nullable|integer|exists:tenant.communication_providers,id',
        ];
    }
}
