<?php

declare(strict_types=1);

namespace Modules\User\Http\Requests\Permission;

use App\Http\Requests\BaseFormRequest;

final class PermissionColumnsRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'columns' => 'present|array',
            'columns.*' => 'distinct|integer|exists:landlord.columns,id',
        ];
    }
}
