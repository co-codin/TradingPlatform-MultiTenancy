<?php

declare(strict_types=1);

namespace Modules\User\Http\Requests;

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
            'users.*.first_name' => 'sometimes|required|string|max:255',
            'users.*.last_name' => 'sometimes|required|string|max:255',
            'users.*.email' => "sometimes|required|email|max:255|unique:landlord.users,email",
            'users.*.is_active' => 'boolean',
            'users.*.parent_id' => 'integer|exists:landlord.users,id',
            'users.*.change_password' => 'nullable|boolean',
            'users.*.password' => 'exclude_unless:change_password,true|required|string|confirmed',
            'users.*.roles.*.id' => 'sometimes|required|integer|min:1|exists:landlord.roles,id',
            'users.*.affiliate_id' => 'nullable|integer|exists:landlord.users,id',
            'users.*.show_on_scoreboards' => 'sometimes|required|boolean',
        ];
    }
}
