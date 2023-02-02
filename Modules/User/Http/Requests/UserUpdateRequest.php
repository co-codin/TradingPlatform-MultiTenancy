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
                'max:35',
                "unique:landlord.users,username,{$this->route('worker')}",
                'regex:'.RegexValidationEnum::USERNAME,
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
                'regex:'.RegexValidationEnum::PASSWORD,
            ],
            'roles' => 'sometimes|required|array',
            'roles.*.id' => [
                'required',
                'distinct',
                'integer',
                'min:1',
                'exists:landlord.roles,id',
            ],
            'brands' => 'sometimes|required|array',
            'brands.*.id' => [
                'required',
                'distinct',
                'integer',
                'min:1',
                'exists:landlord.brands,id',
            ],
            'brands.*.pivot.is_default' => [
                'sometimes',
                'required',
                'bool',
            ],
            'affiliate_id' => 'nullable|integer|exists:landlord.users,id',
            'show_on_scoreboards' => 'sometimes|required|boolean',
            'communication_provider_id' => 'nullable|integer|exists:tenant.communication_providers,id',
            'worker_info' => 'sometimes|required|array',
            'worker_info.first_name' => [
                'sometimes',
                'required',
                'string',
                'max:35',
                'regex:'.RegexValidationEnum::NAME,
            ],
            'worker_info.last_name' => [
                'sometimes',
                'required',
                'string',
                'max:35',
                'regex:'.RegexValidationEnum::NAME,
            ],
            'worker_info.email' => [
                'email',
                'max:255',
                "unique:tenant.worker_info,email,{$this->route('worker')}",
            ],
        ];
    }
}
