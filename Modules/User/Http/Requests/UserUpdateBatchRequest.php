<?php

declare(strict_types=1);

namespace Modules\User\Http\Requests;

use App\Enums\RegexValidationEnum;
use App\Http\Requests\BaseFormRequest;

final class UserUpdateBatchRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'users' => 'required|max:255',
            'users.*.id' => 'required|integer|exists:landlord.users,id',
            'users.*.pivot.first_name' => [
                'sometimes',
                'required',
                'string',
                'max:35',
                'regex:'.RegexValidationEnum::NAME,
            ],
            'users.*.pivot.last_name' => [
                'sometimes',
                'required',
                'string',
                'max:35',
                'regex:'.RegexValidationEnum::NAME,
            ],
            'users.*.pivot.email' => 'sometimes|required|email|max:255|unique:landlord.users,email',
            'users.*.pivot.is_active' => 'boolean',
            'users.*.pivot.parent_id' => 'integer|exists:landlord.users,id',
            'users.*.pivot.change_password' => 'nullable|boolean',
            'users.*.pivot.password' => [
                'exclude_unless:change_password,true',
                'required',
                'string',
                'regex:'.RegexValidationEnum::PASSWORD,
            ],
            'users.*.pivot.roles' => 'sometimes|required|array',
            'users.*.pivot.roles.*.id' => 'required|distinct|integer|min:1|exists:landlord.roles,id',
            'users.*.pivot.affiliate_id' => 'nullable|integer|exists:landlord.users,id',
            'users.*.pivot.show_on_scoreboards' => 'sometimes|required|boolean',
            'users.*.pivot.communication_provider_id' => 'nullable|integer|exists:tenant.communication_providers,id',
        ];
    }
}
