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
            'users.*.worker_info' => 'required|array',
            'users.*.worker_info.first_name' => [
                'sometimes',
                'required',
                'string',
                'max:35',
                'regex:'.RegexValidationEnum::NAME,
            ],
            'users.*.worker_info.last_name' => [
                'sometimes',
                'required',
                'string',
                'max:35',
                'regex:'.RegexValidationEnum::NAME,
            ],
            'users.*.worker_info.email' => 'sometimes|required|email|max:255|unique:tenant.worker_info,email',
            'users.*.is_active' => 'boolean',
            'users.*.parent_id' => 'integer|exists:landlord.users,id',
            'users.*.change_password' => 'nullable|boolean',
            'users.*.password' => [
                'exclude_unless:change_password,true',
                'required',
                'string',
                'regex:'.RegexValidationEnum::PASSWORD,
            ],
            'users.*.roles' => 'sometimes|required|array',
            'users.*.roles.*.id' => 'required|distinct|integer|min:1|exists:landlord.roles,id',
            'users.*.affiliate_id' => 'nullable|integer|exists:landlord.users,id',
            'users.*.show_on_scoreboards' => 'sometimes|required|boolean',
            'users.*.communication_provider_id' => 'nullable|integer|exists:tenant.communication_providers,id',
        ];
    }
}
