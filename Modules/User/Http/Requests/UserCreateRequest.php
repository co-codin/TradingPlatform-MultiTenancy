<?php

declare(strict_types=1);

namespace Modules\User\Http\Requests;

use App\Enums\RegexValidationEnum;
use App\Http\Requests\BaseFormRequest;

final class UserCreateRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'username' => [
                'required',
                'string',
                'max:35',
                'iunique:landlord.users,username',
                'regex:'.RegexValidationEnum::USERNAME,
            ],
            'worker_info' => 'sometimes|array',
            'worker_info.first_name' => [
                'required',
                'string',
                'max:35',
                'regex:'.RegexValidationEnum::NAME,
            ],
            'worker_info.last_name' => [
                'required',
                'string',
                'max:35',
                'regex:'.RegexValidationEnum::NAME,
            ],
            'worker_info.email' => [
                'email',
                'max:255',
                'unique:tenant.worker_info,email',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:'.RegexValidationEnum::PASSWORD,
            ],
            'is_active' => 'boolean',
            'target' => 'numeric',
            'parent_id' => 'integer|exists:landlord.users,id',
            'roles' => 'required|array',
            'roles.*.id' => [
                'required',
                'distinct',
                'integer',
                'min:1',
                'exists:landlord.roles,id',
            ],
            'desks' => 'sometimes|required|array',
            'desks.*.id' => [
                'required',
                'distinct',
                'integer',
                'min:1',
                'exists:tenant.desks,id',
            ],
            'languages' => 'sometimes|required|array',
            'languages.*.id' => [
                'required',
                'distinct',
                'integer',
                'min:1',
                'exists:landlord.languages,id',
            ],
            'countries' => 'sometimes|required|array',
            'countries.*.id' => [
                'required',
                'distinct',
                'integer',
                'min:1',
                'exists:landlord.countries,id',
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
        ];
    }
}
