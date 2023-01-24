<?php

declare(strict_types=1);

namespace Modules\Role\Http\Requests;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;
use Modules\Brand\Models\Brand;

final class RoleUpdateRequest extends BaseFormRequest
{
    /**
     * {@inheritDoc}
     */
    final public function rules(): array
    {
        return [
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:35',
                Rule::unique('landlord.roles')->ignore($this->route('role'))->where(
                    fn ($query) => $query->where(['guard_name' => $this->guard_name, 'brand_id' => Brand::current()?->id])
                ),
            ],
            'key' => 'sometimes|required|string|regex:/^[\w-]+$/|max:35',
            'guard_name' => [
                'sometimes',
                'required',
                'string',
                'max:35',
                Rule::unique('landlord.roles')->ignore($this->route('role'))->where(
                    fn ($query) => $query->where(['name' => $this->name, 'brand_id' => Brand::current()?->id])
                ),
            ],
            'is_default' => 'sometimes|required|boolean',
            'permissions' => 'sometimes|required|array',
            'permissions.*.id' => 'required|exists:landlord.permissions,id',
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
