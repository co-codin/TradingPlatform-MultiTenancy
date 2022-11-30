<?php

declare(strict_types=1);

namespace Modules\Role\Http\Requests;

use App\Http\Requests\BaseFormRequest;

final class RoleUpdateRequest extends BaseFormRequest
{
    /**
     * {@inheritDoc}
     */
    final public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'key' => 'sometimes|required|string|max:255|regex:/[A-Z0-9_-]+/',
            'guard_name' => 'sometimes|nullable|string|max:255',
            'permissions' => 'sometimes|required|array',
            'permissions.*.id' => 'required|exists:permissions,id',
        ];
    }

    /**
     * {@inheritDoc}
     */
    final public function attributes(): array
    {
        return [
            'name' => 'Role name',
            'guard_name' => 'guard_name',
            'permissions' => 'Permissions',
            'permissions.*.id' => 'Permission id',
        ];
    }
}
