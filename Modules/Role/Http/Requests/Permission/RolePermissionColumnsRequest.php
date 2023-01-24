<?php

declare(strict_types=1);

namespace Modules\Role\Http\Requests\Permission;

use App\Http\Requests\BaseFormRequest;

final class RolePermissionColumnsRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'columns' => 'present|array',
            'columns.*' => 'distinct|integer|exists:landlord.columns,id',
        ];
    }
}
